<?php

namespace App\Console\Commands;

use App\Models\ScheduledReport;
use App\Services\ReportBuilderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GenerateScheduledReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and email scheduled reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled reports...');

        $scheduledReports = ScheduledReport::dueToRun()->get();

        if ($scheduledReports->isEmpty()) {
            $this->info('No scheduled reports due to run.');
            return 0;
        }

        $this->info("Found {$scheduledReports->count()} scheduled report(s) to generate.");

        foreach ($scheduledReports as $scheduledReport) {
            try {
                $this->info("Generating report: {$scheduledReport->name}");

                // Set lab context for multi-tenancy
                app()->instance('current_lab_id', $scheduledReport->lab_id);

                $template = $scheduledReport->template;
                
                // Generate report data
                $reportService = app(\App\Services\ReportBuilderService::class);
                $reportData = $reportService->generateReport(
                    $template->report_type,
                    $template->selected_fields,
                    $template->filters ?? [],
                    $template->sorting ?? [],
                    $template->grouping,
                    $template->formatting ?? []
                );

                // Generate file based on format
                $filePath = $this->generateReportFile($reportData, $scheduledReport);

                // Send email
                $this->sendReportEmail($scheduledReport, $filePath);

                // Mark as run
                $scheduledReport->markAsRun();

                $this->info("✓ Report generated and sent successfully.");

            } catch (\Exception $e) {
                $this->error("✗ Error generating report {$scheduledReport->name}: " . $e->getMessage());
                Log::error("Scheduled report generation failed", [
                    'scheduled_report_id' => $scheduledReport->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        $this->info('Scheduled reports processing completed.');
        return 0;
    }

    /**
     * Generate report file.
     */
    protected function generateReportFile(array $reportData, ScheduledReport $scheduledReport): string
    {
        $template = $scheduledReport->template;
        $filename = str_replace(' ', '_', $scheduledReport->name) . '_' . date('Y-m-d_His');
        
        switch ($scheduledReport->format) {
            case 'pdf':
                return $this->generatePdf($reportData, $template, $filename);
            case 'excel':
                return $this->generateExcel($reportData, $template, $filename);
            case 'csv':
                return $this->generateCsv($reportData, $template, $filename);
            default:
                return $this->generatePdf($reportData, $template, $filename);
        }
    }

    /**
     * Generate PDF file.
     */
    protected function generatePdf(array $reportData, $template, string $filename): string
    {
        // For now, return a simple implementation
        // Can be enhanced with DomPDF or similar
        $html = view('report-builder.pdf-email', [
            'data' => $reportData,
            'template' => $template,
        ])->render();

        $filePath = storage_path('app/reports/scheduled/' . $filename . '.html');
        \File::ensureDirectoryExists(dirname($filePath));
        file_put_contents($filePath, $html);

        return $filePath;
    }

    /**
     * Generate Excel file.
     */
    protected function generateExcel(array $reportData, $template, string $filename): string
    {
        // Similar to CSV for now
        return $this->generateCsv($reportData, $template, $filename);
    }

    /**
     * Generate CSV file.
     */
    protected function generateCsv(array $reportData, $template, string $filename): string
    {
        $filePath = storage_path('app/reports/scheduled/' . $filename . '.csv');
        \File::ensureDirectoryExists(dirname($filePath));

        $file = fopen($filePath, 'w');
        
        // Write headers
        fputcsv($file, array_values($reportData['columns']));
        
        // Write data
        foreach ($reportData['rows'] as $row) {
            $values = [];
            foreach (array_keys($reportData['columns']) as $field) {
                $values[] = $row[$field] ?? '';
            }
            fputcsv($file, $values);
        }
        
        fclose($file);

        return $filePath;
    }

    /**
     * Send report email.
     */
    protected function sendReportEmail(ScheduledReport $scheduledReport, string $filePath): void
    {
        $template = $scheduledReport->template;
        
        foreach ($scheduledReport->recipients as $recipient) {
            try {
                Mail::send('emails.scheduled-report', [
                    'scheduledReport' => $scheduledReport,
                    'template' => $template,
                ], function ($message) use ($scheduledReport, $filePath, $recipient) {
                    $message->to($recipient)
                        ->subject($scheduledReport->name . ' - ' . date('Y-m-d'));
                    
                    if (file_exists($filePath)) {
                        $message->attach($filePath);
                    }
                });
            } catch (\Exception $e) {
                Log::error("Failed to send scheduled report email", [
                    'recipient' => $recipient,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
