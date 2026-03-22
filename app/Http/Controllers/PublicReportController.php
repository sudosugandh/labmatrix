<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Report;
use App\Models\WebsiteSetting;
use App\Models\Menu;
use App\Models\Lab;
use App\Models\TestOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PublicReportController extends Controller
{
    /**
     * Show the report search page.
     */
    public function search()
    {
        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        return view('website.reports.search', compact('settings', 'menuItems'));
    }

    /**
     * Show reports for a patient by phone number.
     */
    public function results(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
        ]);

        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        // Find patient by phone number for current lab
        $patient = Patient::where('lab_id', currentLabId())
            ->where('phone', $validated['phone'])
            ->first();

        if (!$patient) {
            return view('website.reports.results', compact('settings', 'menuItems'))
                ->with('error', 'No patient found with this phone number.');
        }

        // Get reports for this patient - only released reports
        $reports = Report::where('lab_id', currentLabId())
            ->where('patient_id', $patient->id)
            ->where('workflow_status', 'released')
            ->where('status', 'ready')
            ->with(['testOrder.test', 'reportTests.test'])
            ->latest()
            ->get();

        return view('website.reports.results', compact('settings', 'menuItems', 'patient', 'reports'));
    }

    /**
     * Download report securely.
     * Regenerates PDF with latest template to ensure branding and QR codes are included.
     */
    public function download($id)
    {
        $report = Report::where('lab_id', currentLabId())
            ->where('id', $id)
            ->where('workflow_status', 'released')
            ->where('status', 'ready')
            ->firstOrFail();

        // Load report relationships
        $report->load([
            'testOrder.patient', 
            'testOrder.test', 
            'testOrder.testResults.testParameter', 
            'testOrder.doctor', 
            'testOrder.lab',
            'patient',
            'reportTests.testOrder.test',
            'reportTests.testOrder.testResults.testParameter'
        ]);

        $settings = WebsiteSetting::getForCurrentLab();
        $lab = $report->lab ?? Lab::find(currentLabId());
        
        // Generate report number if not exists
        if (!$report->report_number) {
            $year = $report->created_at->format('Y');
            $month = $report->created_at->format('m');
            $lastReport = Report::where('lab_id', $report->lab_id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('id', 'desc')
                ->first();
            $sequence = $lastReport && $lastReport->report_number ? 
                (int)substr($lastReport->report_number, -4) + 1 : 1;
            $reportNumber = 'RPT-' . $report->lab_id . '-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        } else {
            $reportNumber = $report->report_number;
        }
        
        // Generate QR code data URI helper
        $generateQrCode = function($reportNumber, $orderId) {
            try {
                $verificationUrl = url('/patient-reports') . '?report=' . $reportNumber;
                $qrData = $reportNumber . '|' . $verificationUrl;
                $qrCode = QrCode::format('svg')->size(150)->margin(2)->generate($qrData);
                return 'data:image/svg+xml;base64,' . base64_encode($qrCode);
            } catch (\Exception $e) {
                return '';
            }
        };
        
        // Check if this is a multi-test report
        if ($report->reportTests && $report->reportTests->count() > 0) {
            // Multi-test report
            $testOrders = $report->reportTests->map(function($rt) {
                return $rt->testOrder;
            })->filter();
            
            $patient = $report->patient;
            $qrCodeData = $generateQrCode($reportNumber, $testOrders->pluck('id')->implode('-'));
            
            $pdf = Pdf::loadView('reports.multi-test-pdf-template', [
                'report' => $report,
                'patient' => $patient,
                'testOrders' => $testOrders,
                'lab' => $lab,
                'settings' => $settings,
                'qrCodeData' => $qrCodeData,
            ]);
        } elseif ($report->testOrder) {
            // Single test report
            $order = $report->testOrder;
            $testResults = $order->testResults;
            $qrCodeData = $generateQrCode($reportNumber, $order->id);
            
            $reportView = ($settings->report_template_style ?? 'lal_pathlabs') === 'default' ? 'reports.pdf-template' : 'reports.pdf-template-lal-pathlabs';
            $pdf = Pdf::loadView($reportView, [
                'report' => $report,
                'patient' => $report->patient ?? $order->patient,
                'testOrder' => $order,
                'testResults' => $testResults,
                'lab' => $lab,
                'settings' => $settings,
                'qrCodeData' => $qrCodeData,
            ]);
        } else {
            // Fallback: If no testOrder, try to download existing file
            if (Storage::disk('local')->exists($report->report_file)) {
                return Storage::disk('local')->download($report->report_file, $report->report_name ?? 'report-' . $report->id . '.pdf');
            }

            $fullPath = storage_path('app/' . $report->report_file);
            if (file_exists($fullPath)) {
                return response()->download($fullPath, $report->report_name ?? 'report-' . $report->id . '.pdf');
            }

            abort(404, 'Report file not found. Please contact the lab administrator.');
        }

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $filename = ($report->report_name ?? 'report-' . $report->id) . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Send report download link to the given email.
     */
    public function sendReportLinkByEmail(Request $request)
    {
        $validated = $request->validate([
            'report_id' => 'required|integer',
            'email' => 'required|email',
        ]);

        $report = Report::where('lab_id', currentLabId())
            ->where('id', $validated['report_id'])
            ->where('workflow_status', 'released')
            ->where('status', 'ready')
            ->first();

        if (!$report) {
            return redirect()->route('public.reports.search')
                ->with('error', 'Report not found or not available.');
        }

        $downloadUrl = route('public.reports.download', $report->id);
        $labName = $report->lab->name ?? currentLab()?->name ?? 'Lab';

        try {
            Mail::raw(
                "Hello,\n\nYour diagnostic report download link from {$labName}:\n\n{$downloadUrl}\n\nThis link is for your use only. If you did not request this, please ignore this email.\n\n— {$labName}",
                function ($message) use ($validated, $labName) {
                    $message->to($validated['email'])
                        ->subject("Your report download link – {$labName}");
                }
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'We could not send the email. Please try again or use the download button.');
        }

        return redirect()->back()->with('email_sent', 'We’ve sent the download link to ' . $validated['email']);
    }
}
