<?php

namespace App\Services;

use App\Models\TestResult;
use App\Models\TestParameter;
use App\Models\TestOrder;

class TestResultService
{
    /**
     * Evaluate result value against reference range
     */
    public function evaluateResult($value, TestParameter $parameter): string
    {
        if ($value === null || $value === '') {
            return 'normal';
        }

        $numericValue = $this->extractNumericValue($value);
        
        if ($numericValue === null) {
            return 'normal'; // Non-numeric values default to normal
        }

        $low = $parameter->reference_range_low;
        $high = $parameter->reference_range_high;

        // If no reference range, default to normal
        if ($low === null && $high === null) {
            return 'normal';
        }

        // Check if value is below low range
        if ($low !== null && $numericValue < $low) {
            // Check if it's critically low (more than 20% below)
            if ($numericValue < ($low * 0.8)) {
                return 'critical';
            }
            return 'low';
        }

        // Check if value is above high range
        if ($high !== null && $numericValue > $high) {
            // Check if it's critically high (more than 20% above)
            if ($numericValue > ($high * 1.2)) {
                return 'critical';
            }
            return 'high';
        }

        return 'normal';
    }

    /**
     * Extract numeric value from string
     */
    private function extractNumericValue($value): ?float
    {
        // Remove common non-numeric characters but keep decimal point
        $cleaned = preg_replace('/[^0-9.]/', '', (string)$value);
        
        if ($cleaned === '' || $cleaned === '.') {
            return null;
        }

        $numeric = (float)$cleaned;
        
        return is_numeric($numeric) ? $numeric : null;
    }

    /**
     * Auto-load parameters for a test order
     */
    public function loadParametersForOrder(TestOrder $testOrder): void
    {
        $test = $testOrder->test;
        
        if (!$test) {
            return;
        }

        $parameters = $test->parameters()->get();

        foreach ($parameters as $parameter) {
            // Check if result already exists for this parameter
            $existingResult = TestResult::where('test_order_id', $testOrder->id)
                ->where('test_parameter_id', $parameter->id)
                ->first();

            if (!$existingResult) {
                TestResult::create([
                    'lab_id' => $testOrder->lab_id,
                    'test_order_id' => $testOrder->id,
                    'test_parameter_id' => $parameter->id,
                    'parameter_name' => $parameter->parameter_name,
                    'method_name' => $parameter->method_name,
                    'unit' => $parameter->unit,
                    'reference_range' => $parameter->formatted_reference_range,
                    'status' => 'normal',
                ]);
            }
        }
    }

    /**
     * Save result and auto-evaluate
     */
    public function saveResult(array $data): TestResult
    {
        $result = TestResult::findOrFail($data['result_id']);
        
        $result->result_value = $data['result_value'];
        $result->entered_by = auth()->id();
        $result->entered_at = now();

        // If parameter exists, evaluate the result
        if ($result->testParameter) {
            $status = $this->evaluateResult($data['result_value'], $result->testParameter);
            $result->status = $status;
        }

        $result->save();

        return $result;
    }

    /**
     * Save multiple results at once
     */
    public function saveResults(array $results, int $testOrderId): void
    {
        foreach ($results as $resultData) {
            if (isset($resultData['result_id']) && isset($resultData['result_value'])) {
                $this->saveResult([
                    'result_id' => $resultData['result_id'],
                    'result_value' => $resultData['result_value'],
                ]);
            }
        }
    }
}
