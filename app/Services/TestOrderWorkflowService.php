<?php

namespace App\Services;

use App\Models\TestOrder;

class TestOrderWorkflowService
{
    /**
     * Get the next allowed status based on current state
     */
    public function getNextAllowedStatus(TestOrder $order): array
    {
        $currentStatus = $order->status;
        $currentSampleStatus = $order->sample_status ?? 'pending';
        $currentReportStatus = $order->report_status ?? 'pending';

        $allowedStatuses = [];
        $allowedSampleStatuses = [];
        $allowedReportStatuses = [];

        // Define status progression
        switch ($currentStatus) {
            case 'pending':
                $allowedStatuses = ['pending', 'sample_collected'];
                $allowedSampleStatuses = ['pending', 'collected'];
                $allowedReportStatuses = ['pending'];
                break;

            case 'sample_collected':
                $allowedStatuses = ['sample_collected', 'processing'];
                $allowedSampleStatuses = ['collected', 'sent_to_lab'];
                $allowedReportStatuses = ['pending'];
                break;

            case 'processing':
                $allowedStatuses = ['processing', 'completed'];
                $allowedSampleStatuses = ['sent_to_lab'];
                $allowedReportStatuses = ['pending'];
                break;

            case 'completed':
                $allowedStatuses = ['completed', 'report_uploaded'];
                $allowedSampleStatuses = ['sent_to_lab'];
                $allowedReportStatuses = ['pending', 'completed'];
                break;

            case 'report_uploaded':
                $allowedStatuses = ['report_uploaded'];
                $allowedSampleStatuses = ['sent_to_lab'];
                $allowedReportStatuses = ['completed'];
                break;
        }

        return [
            'statuses' => $allowedStatuses,
            'sample_statuses' => $allowedSampleStatuses,
            'report_statuses' => $allowedReportStatuses,
        ];
    }

    /**
     * Validate and apply workflow transition
     */
    public function canTransition(TestOrder $order, string $newStatus, ?string $newSampleStatus = null, ?string $newReportStatus = null): array
    {
        $allowed = $this->getNextAllowedStatus($order);
        $errors = [];

        // Validate status transition
        if (!in_array($newStatus, $allowed['statuses'])) {
            $errors[] = "Cannot transition from '{$order->status}' to '{$newStatus}'. Allowed: " . implode(', ', $allowed['statuses']);
        }

        // Validate sample status
        if ($newSampleStatus !== null) {
            if (!in_array($newSampleStatus, $allowed['sample_statuses'])) {
                $errors[] = "Sample status '{$newSampleStatus}' is not allowed in current workflow state.";
            }

            // Enforce sample collection before processing
            if ($newStatus === 'processing' && $newSampleStatus !== 'collected' && $newSampleStatus !== 'sent_to_lab') {
                $errors[] = "Sample must be collected or sent to lab before processing.";
            }

            // Enforce sample collection before sample_collected status
            if ($newStatus === 'sample_collected' && $newSampleStatus !== 'collected') {
                $errors[] = "Sample status must be 'collected' when order status is 'sample_collected'.";
            }
        }

        // Validate report status
        if ($newReportStatus !== null) {
            if (!in_array($newReportStatus, $allowed['report_statuses'])) {
                $errors[] = "Report status '{$newReportStatus}' is not allowed in current workflow state.";
            }

            // Enforce test completion before report completion
            if ($newReportStatus === 'completed' && $newStatus !== 'completed' && $newStatus !== 'report_uploaded') {
                $errors[] = "Test must be completed before report can be marked as completed.";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Apply workflow transition with auto-updates
     */
    public function applyTransition(TestOrder $order, string $newStatus, ?string $newSampleStatus = null, ?string $newReportStatus = null): array
    {
        $validation = $this->canTransition($order, $newStatus, $newSampleStatus, $newReportStatus);

        if (!$validation['valid']) {
            return $validation;
        }

        $updates = ['status' => $newStatus];

        // Auto-update sample status based on order status
        if ($newStatus === 'sample_collected' && $newSampleStatus === null) {
            $updates['sample_status'] = 'collected';
            $updates['sample_collected_at'] = now();
        } elseif ($newStatus === 'processing' && ($order->sample_status === 'pending' || $order->sample_status === null)) {
            $updates['sample_status'] = 'collected';
            if (!$order->sample_collected_at) {
                $updates['sample_collected_at'] = now();
            }
        } elseif ($newSampleStatus !== null) {
            $updates['sample_status'] = $newSampleStatus;
            if ($newSampleStatus === 'collected' && !$order->sample_collected_at) {
                $updates['sample_collected_at'] = now();
            }
        }

        // Auto-update report status
        if ($newReportStatus !== null) {
            $updates['report_status'] = $newReportStatus;
            
            // Auto-update order status to "report_uploaded" when report is completed
            // This should happen if:
            // 1. Report status is being set to 'completed' AND
            // 2. Order status is 'completed' (either from newStatus or current order status)
            if ($newReportStatus === 'completed') {
                if ($newStatus === 'completed' || $order->status === 'completed') {
                    $updates['status'] = 'report_uploaded';
                }
            }
        } elseif ($newStatus === 'report_uploaded' && $order->report_status === 'pending') {
            $updates['report_status'] = 'completed';
        }

        return [
            'valid' => true,
            'updates' => $updates,
        ];
    }

    /**
     * Get workflow steps for display
     */
    public function getWorkflowSteps(TestOrder $order): array
    {
        $steps = [
            [
                'name' => 'Order Created',
                'status' => 'completed',
                'icon' => 'check-circle',
                'color' => 'green',
            ],
            [
                'name' => 'Sample Collected',
                'status' => $order->sample_status === 'collected' || $order->sample_status === 'sent_to_lab' ? 'completed' : ($order->status === 'pending' ? 'pending' : 'current'),
                'icon' => $order->sample_status === 'collected' || $order->sample_status === 'sent_to_lab' ? 'check-circle' : 'clock',
                'color' => $order->sample_status === 'collected' || $order->sample_status === 'sent_to_lab' ? 'green' : ($order->status === 'pending' ? 'gray' : 'blue'),
            ],
            [
                'name' => 'Processing',
                'status' => $order->status === 'completed' || $order->status === 'report_uploaded' ? 'completed' : ($order->status === 'processing' ? 'current' : 'pending'),
                'icon' => $order->status === 'completed' || $order->status === 'report_uploaded' ? 'check-circle' : ($order->status === 'processing' ? 'cog' : 'clock'),
                'color' => $order->status === 'completed' || $order->status === 'report_uploaded' ? 'green' : ($order->status === 'processing' ? 'blue' : 'gray'),
            ],
            [
                'name' => 'Test Completed',
                'status' => $order->status === 'report_uploaded' ? 'completed' : ($order->status === 'completed' ? 'current' : 'pending'),
                'icon' => $order->status === 'report_uploaded' ? 'check-circle' : ($order->status === 'completed' ? 'check-circle' : 'clock'),
                'color' => $order->status === 'report_uploaded' ? 'green' : ($order->status === 'completed' ? 'blue' : 'gray'),
            ],
            [
                'name' => 'Report Ready',
                'status' => $order->report_status === 'completed' ? 'completed' : ($order->status === 'report_uploaded' ? 'current' : 'pending'),
                'icon' => $order->report_status === 'completed' ? 'check-circle' : 'clock',
                'color' => $order->report_status === 'completed' ? 'green' : 'gray',
            ],
        ];

        return $steps;
    }
}
