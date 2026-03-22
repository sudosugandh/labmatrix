<?php

namespace App\Services;

use App\Http\Controllers\ReportBuilderController;
use Illuminate\Support\Facades\DB;

class ReportBuilderService
{
    /**
     * Generate report data with formatting.
     */
    public function generateReport(
        string $reportType,
        array $selectedFields,
        array $filters,
        array $sorting,
        ?array $grouping,
        array $formatting = []
    ): array {
        $controller = app(ReportBuilderController::class);
        
        // Use reflection to call protected method
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateReportData');
        $method->setAccessible(true);
        
        $data = $method->invoke($controller, $reportType, $selectedFields, $filters, $sorting, $grouping, null);
        
        // Apply formatting
        if (!empty($formatting)) {
            $data = $this->applyFormatting($data, $formatting, $reportType);
        }
        
        return $data;
    }

    /**
     * Apply formatting to report data.
     */
    protected function applyFormatting(array $data, array $formatting, string $reportType): array
    {
        $dateFormat = $formatting['date_format'] ?? 'Y-m-d';
        $numberFormat = $formatting['number_format'] ?? '2';
        $currencySymbol = $formatting['currency_symbol'] ?? '₹';
        
        $availableFields = $this->getAvailableFields($reportType);
        
        foreach ($data['rows'] as &$row) {
            foreach ($row as $field => &$value) {
                if ($value === null || $value === '') {
                    continue;
                }
                
                $fieldConfig = $availableFields[$field] ?? null;
                
                // Format dates
                if ($fieldConfig && $fieldConfig['type'] === 'date' && $value) {
                    try {
                        $date = \Carbon\Carbon::parse($value);
                        $value = $date->format($dateFormat);
                    } catch (\Exception $e) {
                        // Keep original value if parsing fails
                    }
                }
                
                // Format numbers
                if ($fieldConfig && $fieldConfig['type'] === 'number' && is_numeric($value)) {
                    $value = number_format((float)$value, (int)$numberFormat);
                    
                    // Add currency symbol for price/amount fields
                    if (str_contains($field, 'price') || str_contains($field, 'amount') || str_contains($field, 'commission')) {
                        $value = $currencySymbol . ' ' . $value;
                    }
                }
            }
        }
        
        return $data;
    }

    /**
     * Get available fields for a report type.
     */
    protected function getAvailableFields(string $reportType): array
    {
        $controller = app(ReportBuilderController::class);
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('getAvailableFields');
        $method->setAccessible(true);
        
        return $method->invoke($controller, $reportType);
    }
}
