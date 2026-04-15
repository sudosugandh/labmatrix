<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Patient;
use App\Models\Report;
use App\Models\ReportTest;
use App\Models\TestOrder;
use App\Models\WebsiteSetting;
use App\Services\AlertService;
use App\Services\LabQuota;
use App\Services\ReportTemplateService;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $labId = currentLabId();

        $query = Report::where('lab_id', $labId)
            ->with(['patient', 'testOrder.test']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_name', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($patientQuery) use ($search) {
                        $patientQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('testOrder.test', function ($testQuery) use ($search) {
                        $testQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Report::where('lab_id', $labId)->count(),
            'ready' => Report::where('lab_id', $labId)->where('status', 'ready')->count(),
            'pending' => Report::where('lab_id', $labId)->where('status', 'pending')->count(),
            'today' => Report::where('lab_id', $labId)->whereDate('created_at', today())->count(),
        ];

        return view('reports.index', compact('reports', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TestOrder $order)
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        // Check if report already exists
        if ($order->report) {
            return redirect()->route('reports.show', $order->report)
                ->with('info', 'Report already exists for this test order.');
        }

        $order->load(['patient', 'test', 'testResults', 'doctor', 'lab']);

        return view('reports.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TestOrder $order): RedirectResponse
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'report_name' => 'nullable|string|max:255',
            'report_file' => 'nullable|file|mimes:pdf|max:10240',
            'generate_pdf' => 'nullable|boolean',
            'remarks' => 'nullable|string',
            'status' => 'nullable|in:pending,ready',
        ]);

        $lab = Lab::findOrFail(currentLabId());

        // Generate PDF or use uploaded file
        if ($request->boolean('generate_pdf')) {
            $filePath = $this->generatePdfReport($order, $validated);
            try {
                LabQuota::assertCanUseStorageBytes($lab, (int) Storage::disk('local')->size($filePath));
            } catch (ValidationException $e) {
                Storage::disk('local')->delete($filePath);

                return redirect()->back()
                    ->withErrors($e->errors())
                    ->withInput();
            }
        } else {
            if (! $request->hasFile('report_file')) {
                return redirect()->back()
                    ->withErrors(['report_file' => 'Please upload a PDF file or generate one.'])
                    ->withInput();
            }
            LabQuota::assertCanUseStorageBytes($lab, (int) $request->file('report_file')->getSize());
            $filePath = $request->file('report_file')->store('reports/'.currentLabId(), 'local');
        }

        $validated['report_file'] = $filePath;

        $validated['lab_id'] = currentLabId();
        $validated['patient_id'] = $order->patient_id;
        $validated['test_order_id'] = $order->id;
        $validated['report_name'] = $validated['report_name'] ?? $order->test->name.' Report';
        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['workflow_status'] = 'draft';
        $validated['report_number'] = $this->generateReportNumber();
        $validated['uploaded_by'] = Auth::id();

        $report = Report::create($validated);

        // Create report_test entry for single test
        ReportTest::create([
            'report_id' => $report->id,
            'test_order_id' => $order->id,
            'test_id' => $order->test_id,
            'sort_order' => 0,
        ]);

        // Update test order status and report status
        $order->update([
            'report_status' => 'completed',
            'status' => 'report_uploaded',
        ]);

        // Send notifications if report is ready
        if ($validated['status'] === 'ready') {
            try {
                // Send WhatsApp notification (existing)
                $whatsappService = app(WhatsAppService::class);
                $whatsappService->sendReportReady($report);

                // Send Email/SMS via AlertService
                $alertService = app(AlertService::class);
                $alertService->sendTestResultReady($order, $report);
            } catch (\Exception $e) {
                \Log::error('Notification failed: '.$e->getMessage());
            }
        }

        return redirect()->route('reports.show', $report)
            ->with('success', 'Report uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        // Ensure report belongs to current lab
        if ($report->lab_id !== currentLabId()) {
            abort(404);
        }

        $report->load([
            'patient',
            'testOrder.test',
            'testOrder.testResults.testParameter',
            'reportTests.test',
            'reportTests.testOrder.testResults.testParameter',
            'reviewedBy',
            'releasedBy',
            'uploadedBy',
        ]);

        return view('reports.show', compact('report'));
    }

    /**
     * Download the report file.
     * Regenerates PDF with latest template to ensure branding and QR codes are included.
     */
    public function download(Report $report)
    {
        // Ensure report belongs to current lab
        if ($report->lab_id !== currentLabId()) {
            abort(404);
        }

        // Load report relationships
        $report->load([
            'testOrder.patient',
            'testOrder.test',
            'testOrder.testResults.testParameter',
            'testOrder.doctor',
            'testOrder.lab',
            'patient',
            'reportTests.testOrder.test',
            'reportTests.testOrder.testResults.testParameter',
        ]);

        $settings = WebsiteSetting::getForCurrentLab();
        $lab = $report->lab ?? Lab::find(currentLabId());

        // Generate report number if not exists
        $reportNumber = $report->report_number ?? $this->generateReportNumber();

        // Multi-test PDF only when several orders are linked (single-test reports still have one report_tests row)
        if ($report->reportTests && $report->reportTests->count() > 1) {
            // Multi-test report
            $testOrders = $report->reportTests->map(function ($rt) {
                return $rt->testOrder;
            })->filter();

            $patient = $report->patient;
            $templateService = app(ReportTemplateService::class);

            // Get templates for each test order
            $testTemplates = [];
            foreach ($testOrders as $order) {
                $template = $templateService->getTemplateForOrder($order);
                if ($template) {
                    $testTemplates[$order->id] = $template;
                }
            }

            // Generate QR code data URI
            $qrCodeData = $this->generateQrCodeDataUri($reportNumber, $testOrders->pluck('id')->implode('-'));

            $pdf = Pdf::loadView('reports.multi-test-pdf-template', [
                'report' => $report,
                'patient' => $patient,
                'testOrders' => $testOrders,
                'lab' => $lab,
                'settings' => $settings,
                'qrCodeData' => $qrCodeData,
                'testTemplates' => $testTemplates,
                'templateService' => $templateService,
            ]);
        } elseif ($report->testOrder) {
            // Single test report
            $order = $report->testOrder;
            $testResults = $order->testResults;
            $templateService = app(ReportTemplateService::class);

            // Get template for this test
            $template = $templateService->getTemplateForOrder($order);

            // Generate QR code data URI
            $qrCodeData = $this->generateQrCodeDataUri($reportNumber, $order->id);

            // Use template-based view if template exists, otherwise use style from settings
            $defaultView = ($settings->report_template_style ?? 'lal_pathlabs') === 'default'
                ? 'reports.pdf-template'
                : 'reports.pdf-template-lal-pathlabs';
            $viewName = $template && $template->sections->count() > 0
                ? 'reports.dynamic-template'
                : $defaultView;

            $pdf = Pdf::loadView($viewName, [
                'report' => $report,
                'patient' => $report->patient ?? $order->patient,
                'testOrder' => $order,
                'testResults' => $testResults,
                'lab' => $lab,
                'settings' => $settings,
                'qrCodeData' => $qrCodeData,
                'template' => $template,
                'templateService' => $templateService,
            ]);
        } else {
            // Fallback: If no testOrder, try to download existing file
            if (Storage::disk('local')->exists($report->report_file)) {
                return Storage::disk('local')->download($report->report_file, $report->report_name ?? 'report-'.$report->id.'.pdf');
            }

            $fullPath = storage_path('app/'.$report->report_file);
            if (file_exists($fullPath)) {
                return response()->download($fullPath, $report->report_name ?? 'report-'.$report->id.'.pdf');
            }

            abort(404, 'Report file not found.');
        }

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $filename = ($report->report_name ?? 'report-'.$report->id).'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate PDF report from test order data.
     */
    protected function generatePdfReport(TestOrder $order, array $data = []): string
    {
        $order->load([
            'patient',
            'test',
            'testResults.testParameter',
            'doctor',
            'lab',
            'collectedBy',
        ]);

        $lab = $order->lab ?? Lab::find(currentLabId());
        $testResults = $order->testResults;
        $settings = WebsiteSetting::getForCurrentLab();
        $templateService = app(ReportTemplateService::class);
        $template = $templateService->getTemplateForOrder($order);

        // Generate report number
        $reportNumber = $data['report_number'] ?? $this->generateReportNumber();

        // Generate QR code data URI for PDF embedding
        $qrCodeData = $this->generateQrCodeDataUri($reportNumber, $order->id);

        // Match preview/download: use lab diagnostic template when it has sections
        $defaultView = ($settings->report_template_style ?? 'lal_pathlabs') === 'default'
            ? 'reports.pdf-template'
            : 'reports.pdf-template-lal-pathlabs';
        $viewName = $template && $template->sections->count() > 0
            ? 'reports.dynamic-template'
            : $defaultView;

        $pdf = Pdf::loadView($viewName, [
            'report' => (object) [
                'id' => 'TEMP-'.$order->id,
                'report_number' => $reportNumber,
                'status' => $data['status'] ?? 'ready',
                'remarks' => $data['remarks'] ?? null,
                'created_at' => now(),
                'released_at' => now(),
                'workflow_status' => 'released',
                'uploadedBy' => Auth::user(),
            ],
            'patient' => $order->patient,
            'testOrder' => $order,
            'testResults' => $testResults,
            'lab' => $lab,
            'settings' => $settings,
            'qrCodeData' => $qrCodeData,
            'template' => $template,
            'templateService' => $templateService,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        // Generate filename
        $filename = 'report_'.$order->id.'_'.now()->format('YmdHis').'.pdf';
        $labId = currentLabId();
        $filePath = 'reports/'.$labId.'/'.$filename;

        // Save PDF to storage
        Storage::disk('local')->put($filePath, $pdf->output());

        return $filePath;
    }

    /**
     * Preview PDF report before saving.
     */
    public function preview(TestOrder $order)
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        $order->load([
            'patient',
            'test',
            'testResults.testParameter',
            'doctor',
            'lab',
            'collectedBy',
        ]);

        $lab = $order->lab ?? Lab::find(currentLabId());
        $testResults = $order->testResults;
        $settings = WebsiteSetting::getForCurrentLab();
        $templateService = app(ReportTemplateService::class);

        // Get template for this test (same resolution as generatePdfReport / download single-test)
        $template = $templateService->getTemplateForOrder($order);

        // Generate QR code for preview
        $reportNumber = 'PREVIEW-'.$order->id;
        $qrCodeData = $this->generateQrCodeDataUri($reportNumber, $order->id);

        // Use template-based view if template exists, otherwise use style from settings
        $defaultView = ($settings->report_template_style ?? 'lal_pathlabs') === 'default' ? 'reports.pdf-template' : 'reports.pdf-template-lal-pathlabs';
        $viewName = $template && $template->sections->count() > 0 ? 'reports.dynamic-template' : $defaultView;

        $pdf = Pdf::loadView($viewName, [
            'report' => (object) [
                'id' => 'PREVIEW-'.$order->id,
                'report_number' => $reportNumber,
                'status' => 'ready',
                'remarks' => null,
                'created_at' => now(),
                'released_at' => now(),
                'workflow_status' => 'released',
                'uploadedBy' => Auth::user(),
            ],
            'patient' => $order->patient,
            'testOrder' => $order,
            'testResults' => $testResults,
            'lab' => $lab,
            'settings' => $settings,
            'qrCodeData' => $qrCodeData,
            'template' => $template,
            'templateService' => $templateService,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('report-preview-'.$order->id.'.pdf');
    }

    /**
     * Create multi-test report from multiple test orders
     */
    public function createMultiTest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_order_ids' => 'required|array|min:1',
            'test_order_ids.*' => 'exists:test_orders,id',
            'report_name' => 'nullable|string|max:255',
            'interpretation' => 'nullable|string',
        ]);

        $labId = currentLabId();
        $patient = Patient::where('id', $validated['patient_id'])
            ->where('lab_id', $labId)
            ->firstOrFail();

        $testOrders = TestOrder::whereIn('id', $validated['test_order_ids'])
            ->where('lab_id', $labId)
            ->where('patient_id', $validated['patient_id'])
            ->with(['test', 'testResults.testParameter'])
            ->get();

        if ($testOrders->isEmpty()) {
            return redirect()->back()
                ->withErrors(['error' => 'No valid test orders found.'])
                ->withInput();
        }

        // Generate PDF for multi-test report
        $filePath = $this->generateMultiTestPdfReport($testOrders, $patient, $validated);

        try {
            LabQuota::assertCanUseStorageBytes(Lab::findOrFail($labId), (int) Storage::disk('local')->size($filePath));
        } catch (ValidationException $e) {
            Storage::disk('local')->delete($filePath);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $report = Report::create([
            'lab_id' => $labId,
            'patient_id' => $validated['patient_id'],
            'test_order_id' => $testOrders->first()->id, // Primary order
            'report_number' => $this->generateReportNumber(),
            'report_name' => $validated['report_name'] ?? 'Multi-Test Report',
            'report_file' => $filePath,
            'interpretation' => $validated['interpretation'] ?? null,
            'workflow_status' => 'draft',
            'status' => 'pending',
            'uploaded_by' => Auth::id(),
        ]);

        // Create report_test entries
        foreach ($testOrders as $index => $order) {
            ReportTest::create([
                'report_id' => $report->id,
                'test_order_id' => $order->id,
                'test_id' => $order->test_id,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('reports.show', $report)
            ->with('success', 'Multi-test report created successfully.');
    }

    /**
     * Update workflow status
     */
    public function updateWorkflow(Request $request, Report $report): RedirectResponse
    {
        if ($report->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'workflow_status' => 'required|in:draft,reviewed,released',
            'interpretation' => 'nullable|string',
        ]);

        $updateData = [
            'workflow_status' => $validated['workflow_status'],
        ];

        if ($validated['workflow_status'] === 'reviewed') {
            $updateData['reviewed_at'] = now();
            $updateData['reviewed_by'] = Auth::id();
        } elseif ($validated['workflow_status'] === 'released') {
            $updateData['released_at'] = now();
            $updateData['released_by'] = Auth::id();
            $updateData['status'] = 'ready'; // Also update status to ready
        }

        if (isset($validated['interpretation'])) {
            $updateData['interpretation'] = $validated['interpretation'];
        }

        $report->update($updateData);

        return redirect()->back()
            ->with('success', 'Report workflow status updated successfully.');
    }

    /**
     * Update doctor signature
     */
    public function updateDoctorSignature(Request $request, Report $report): RedirectResponse
    {
        if ($report->lab_id !== currentLabId()) {
            abort(404);
        }

        try {
            \Log::info('Updating doctor signature', [
                'report_id' => $report->id,
                'request_data' => $request->except(['doctor_signature']),
                'has_file' => $request->hasFile('doctor_signature'),
            ]);

            $validated = $request->validate([
                'doctor_name' => 'required|string|max:255',
                'doctor_qualification' => 'required|string|max:255',
                'doctor_signature' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            // Handle file upload
            if ($request->hasFile('doctor_signature')) {
                LabQuota::assertCanUseStorageBytes(Lab::findOrFail(currentLabId()), (int) $request->file('doctor_signature')->getSize(), 'doctor_signature');

                // Delete old signature if exists
                if ($report->doctor_signature && Storage::disk('public')->exists($report->doctor_signature)) {
                    Storage::disk('public')->delete($report->doctor_signature);
                }

                // Store new signature
                $signaturePath = $request->file('doctor_signature')->store('doctor-signatures/'.currentLabId(), 'public');
                $validated['doctor_signature'] = $signaturePath;
                \Log::info('File uploaded', ['path' => $signaturePath]);
            }

            // Update report with validated data (only pass DB columns, not file objects)
            $updateData = [
                'doctor_name' => $validated['doctor_name'],
                'doctor_qualification' => $validated['doctor_qualification'],
            ];
            if (isset($validated['doctor_signature']) && is_string($validated['doctor_signature'])) {
                $updateData['doctor_signature'] = $validated['doctor_signature'];
            }
            $updated = $report->update($updateData);

            if (! $updated) {
                \Log::warning('Update returned false', ['report_id' => $report->id]);

                return redirect()->back()
                    ->with('error', 'Failed to update doctor signature. Please try again.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('success', 'Doctor signature updated successfully.');
        } catch (ValidationException $e) {
            \Log::warning('Validation failed', ['errors' => $e->errors()]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Failed to update doctor signature: '.$e->getMessage(), [
                'report_id' => $report->id,
                'error' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while updating doctor signature. Please check the logs for details.')
                ->withInput();
        }
    }

    /**
     * Generate QR code as data URI for PDF embedding
     */
    protected function generateQrCodeDataUri(string $reportNumber, $orderId): string
    {
        try {
            // Create QR code data - include report number and verification URL
            $verificationUrl = url('/patient-reports').'?report='.$reportNumber;
            $qrData = $reportNumber.'|'.$verificationUrl;

            // Use SVG format (default) - no Imagick extension required
            $qrCode = QrCode::format('svg')
                ->size(150)
                ->margin(2)
                ->generate($qrData);

            return 'data:image/svg+xml;base64,'.base64_encode($qrCode);
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR code: '.$e->getMessage());

            return '';
        }
    }

    /**
     * Generate report number
     */
    protected function generateReportNumber(): string
    {
        $labId = currentLabId();
        $year = now()->format('Y');
        $month = now()->format('m');

        // Get last report number for this month
        $lastReport = Report::where('lab_id', $labId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastReport ? (int) substr($lastReport->report_number ?? '0', -4) + 1 : 1;

        return 'RPT-'.$labId.'-'.$year.$month.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate multi-test PDF report
     */
    protected function generateMultiTestPdfReport($testOrders, $patient, array $data = []): string
    {
        $lab = Lab::find(currentLabId());

        $settings = WebsiteSetting::getForCurrentLab();

        // Generate report number
        $reportNumber = $data['report_number'] ?? $this->generateReportNumber();

        // Generate QR code data URI
        $qrCodeData = $this->generateQrCodeDataUri($reportNumber, $testOrders->pluck('id')->implode('-'));

        $pdf = Pdf::loadView('reports.multi-test-pdf-template', [
            'report' => (object) [
                'id' => 'MULTI-'.$testOrders->pluck('id')->implode('-'),
                'report_number' => $reportNumber,
                'status' => $data['status'] ?? 'ready',
                'remarks' => $data['remarks'] ?? null,
                'interpretation' => $data['interpretation'] ?? null,
                'created_at' => now(),
                'uploadedBy' => Auth::user(),
            ],
            'patient' => $patient,
            'testOrders' => $testOrders,
            'lab' => $lab,
            'settings' => $settings,
            'qrCodeData' => $qrCodeData,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $filename = 'multi_test_report_'.$testOrders->pluck('id')->implode('_').'_'.now()->format('YmdHis').'.pdf';
        $labId = currentLabId();
        $filePath = 'reports/'.$labId.'/'.$filename;

        Storage::disk('local')->put($filePath, $pdf->output());

        return $filePath;
    }
}
