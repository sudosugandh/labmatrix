<?php

namespace App\Services;

use App\Models\DiagnosticReportTemplate;
use App\Models\TemplateSection;
use App\Models\TestOrder;
use App\Models\TestResult;
use Illuminate\Support\Collection;

class ReportTemplateService
{
    /**
     * Get template for a test order
     */
    public function getTemplateForOrder(TestOrder $order): ?DiagnosticReportTemplate
    {
        $test = $order->test;
        
        if (!$test) {
            return DiagnosticReportTemplate::getDefaultForLab($order->lab_id);
        }

        $template = $test->getReportTemplate();
        
        // Load sections if template exists
        if ($template) {
            $template->load('sections');
        }
        
        return $template;
    }

    /**
     * Calculate formula-based values
     */
    public function calculateFormula(string $formula, Collection $testResults): ?float
    {
        try {
            // Replace parameter names with actual values
            $calculatedFormula = $formula;
            
            foreach ($testResults as $result) {
                $paramName = strtolower(str_replace(' ', '_', $result->parameter_name));
                $value = $result->result_value;
                
                if (is_numeric($value)) {
                    // Replace parameter name in formula (case-insensitive)
                    $calculatedFormula = preg_replace(
                        '/\b' . preg_quote($paramName, '/') . '\b/i',
                        $value,
                        $calculatedFormula
                    );
                }
            }

            // Evaluate the formula safely
            // Remove any non-numeric, non-operator characters for safety
            $calculatedFormula = preg_replace('/[^0-9+\-*/().\s]/', '', $calculatedFormula);
            
            // Use eval with error handling (in production, consider using a math parser library)
            $result = @eval("return {$calculatedFormula};");
            
            return is_numeric($result) ? (float)$result : null;
        } catch (\Exception $e) {
            \Log::error('Formula calculation failed: ' . $e->getMessage(), [
                'formula' => $formula,
                'test_results' => $testResults->toArray()
            ]);
            return null;
        }
    }

    /**
     * Get interpretation text based on result ranges
     */
    public function getInterpretation(TestOrder $order, array $interpretationRules = []): ?string
    {
        if (empty($interpretationRules)) {
            return null;
        }

        $testResults = $order->testResults;
        
        foreach ($interpretationRules as $rule) {
            $parameter = $rule['parameter'] ?? null;
            $condition = $rule['condition'] ?? null; // 'gt', 'lt', 'eq', 'between'
            $value = $rule['value'] ?? null;
            $interpretation = $rule['interpretation'] ?? null;

            if (!$parameter || !$condition || !$interpretation) {
                continue;
            }

            $result = $testResults->firstWhere('parameter_name', $parameter);
            
            if (!$result || !is_numeric($result->result_value)) {
                continue;
            }

            $resultValue = (float)$result->result_value;
            $match = false;

            switch ($condition) {
                case 'gt':
                    $match = $resultValue > (float)$value;
                    break;
                case 'lt':
                    $match = $resultValue < (float)$value;
                    break;
                case 'eq':
                    $match = abs($resultValue - (float)$value) < 0.001;
                    break;
                case 'between':
                    if (isset($value['min']) && isset($value['max'])) {
                        $match = $resultValue >= (float)$value['min'] && $resultValue <= (float)$value['max'];
                    }
                    break;
            }

            if ($match) {
                return $interpretation;
            }
        }

        return null;
    }

    /**
     * Render section content based on section type
     */
    public function renderSection(TemplateSection $section, TestOrder $order, Collection $testResults): array
    {
        $data = [
            'section' => $section,
            'content' => $section->getFormattedContent(),
        ];

        switch ($section->section_type) {
            case 'parameters':
                $data['testResults'] = $testResults;
                break;

            case 'calculated':
                $formula = $section->content_json['formula'] ?? null;
                if ($formula) {
                    $data['calculated_value'] = $this->calculateFormula($formula, $testResults);
                    $data['formula'] = $formula;
                    $data['unit'] = $section->content_json['unit'] ?? null;
                }
                break;

            case 'interpretation':
                $interpretationRules = $section->content_json['rules'] ?? [];
                $data['interpretation'] = $this->getInterpretation($order, $interpretationRules);
                break;

            case 'text':
                $data['text_content'] = $section->content_json['text'] ?? '';
                break;

            case 'table':
                $data['table_data'] = $section->content_json['table'] ?? [];
                break;

            case 'notes':
                $data['notes'] = $section->content_json['notes'] ?? '';
                break;
        }

        return $data;
    }
}

