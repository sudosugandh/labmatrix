<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\TestOrder;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Test;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index(Request $request): View
    {
        $labId = currentLabId();
        $period = $request->get('period', 'month'); // day, week, month, year

        // Calculate date range based on period
        $dateRange = $this->getDateRange($period);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Revenue Analytics
        $revenue = $this->getRevenueAnalytics($labId, $startDate, $endDate, $period);
        
        // Test Statistics
        $testStats = $this->getTestStatistics($labId, $startDate, $endDate);
        
        // Patient Analytics
        $patientStats = $this->getPatientAnalytics($labId, $startDate, $endDate);
        
        // Payment Analytics
        $paymentStats = $this->getPaymentAnalytics($labId, $startDate, $endDate);
        
        // Appointment Analytics
        $appointmentStats = $this->getAppointmentAnalytics($labId, $startDate, $endDate);
        
        // Package Analytics
        $packageStats = $this->getPackageStatistics($labId, $startDate, $endDate);

        return view('analytics.index', compact(
            'revenue',
            'testStats',
            'patientStats',
            'paymentStats',
            'appointmentStats',
            'packageStats',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get revenue analytics.
     */
    private function getRevenueAnalytics($labId, $startDate, $endDate, $period)
    {
        // Total revenue from bills
        $totalRevenue = Bill::where('lab_id', $labId)
            ->whereBetween('bill_date', [$startDate, $endDate])
            ->sum('total');

        // Paid amount
        $paidAmount = Bill::where('lab_id', $labId)
            ->whereBetween('bill_date', [$startDate, $endDate])
            ->sum('paid_amount');

        // Pending amount
        $pendingAmount = Bill::where('lab_id', $labId)
            ->whereBetween('bill_date', [$startDate, $endDate])
            ->sum('due_amount');

        // Revenue trend data for chart
        $revenueTrend = $this->getRevenueTrend($labId, $startDate, $endDate, $period);

        // Revenue by status
        $revenueByStatus = Bill::where('lab_id', $labId)
            ->whereBetween('bill_date', [$startDate, $endDate])
            ->select('status', DB::raw('SUM(total) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        return [
            'total' => $totalRevenue,
            'paid' => $paidAmount,
            'pending' => $pendingAmount,
            'trend' => $revenueTrend,
            'by_status' => $revenueByStatus,
        ];
    }

    /**
     * Get revenue trend data for charts.
     */
    private function getRevenueTrend($labId, $startDate, $endDate, $period)
    {
        $trend = [];
        $current = Carbon::parse($startDate);

        while ($current <= Carbon::parse($endDate)) {
            if ($period === 'day') {
                $dayStart = $current->copy()->startOfDay();
                $dayEnd = $current->copy()->endOfDay();
                $label = $current->format('M d');
                $next = $current->copy()->addDay();
            } elseif ($period === 'week') {
                $dayStart = $current->copy()->startOfWeek();
                $dayEnd = $current->copy()->endOfWeek();
                $label = 'Week ' . $current->week . ' ' . $current->format('M');
                $next = $current->copy()->addWeek();
            } elseif ($period === 'month') {
                $dayStart = $current->copy()->startOfMonth();
                $dayEnd = $current->copy()->endOfMonth();
                $label = $current->format('M Y');
                $next = $current->copy()->addMonth();
            } else {
                $dayStart = $current->copy()->startOfYear();
                $dayEnd = $current->copy()->endOfYear();
                $label = $current->format('Y');
                $next = $current->copy()->addYear();
            }

            $revenue = Bill::where('lab_id', $labId)
                ->whereBetween('bill_date', [$dayStart->format('Y-m-d'), $dayEnd->format('Y-m-d')])
                ->sum('total');

            $trend[] = [
                'label' => $label,
                'revenue' => (float) $revenue,
            ];

            $current = $next;
            if ($current > Carbon::parse($endDate)) break;
        }

        return $trend;
    }

    /**
     * Get test statistics.
     */
    private function getTestStatistics($labId, $startDate, $endDate)
    {
        // Most ordered tests
        $topTests = TestOrder::where('lab_id', $labId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('test_id', DB::raw('COUNT(*) as count'))
            ->with('test')
            ->groupBy('test_id')
            ->orderByDesc('count')
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'name' => $order->test->name ?? 'Unknown',
                    'count' => $order->count,
                ];
            });

        // Total tests ordered
        $totalTests = TestOrder::where('lab_id', $labId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Tests by status
        $testsByStatus = TestOrder::where('lab_id', $labId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return [
            'total' => $totalTests,
            'top_tests' => $topTests,
            'by_status' => $testsByStatus,
        ];
    }

    /**
     * Get patient analytics.
     */
    private function getPatientAnalytics($labId, $startDate, $endDate)
    {
        // New patients
        $newPatients = Patient::where('lab_id', $labId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Total patients
        $totalPatients = Patient::where('lab_id', $labId)->count();

        // Patient growth trend (simplified - show last 30 days)
        $growthTrend = [];
        $current = Carbon::parse($startDate);
        $maxDays = 30; // Limit to 30 data points
        $dayIncrement = max(1, floor((Carbon::parse($endDate)->diffInDays($current)) / $maxDays));
        
        while ($current <= Carbon::parse($endDate)) {
            $count = Patient::where('lab_id', $labId)
                ->where('created_at', '<=', $current->endOfDay())
                ->count();

            $growthTrend[] = [
                'label' => $current->format('M d'),
                'count' => $count,
            ];

            $current->addDays($dayIncrement);
            if ($current > Carbon::parse($endDate)) break;
        }

        return [
            'new' => $newPatients,
            'total' => $totalPatients,
            'growth_trend' => $growthTrend,
        ];
    }

    /**
     * Get payment analytics.
     */
    private function getPaymentAnalytics($labId, $startDate, $endDate)
    {
        // Total payments
        $totalPayments = Payment::where('lab_id', $labId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        // Payments by method
        $paymentsByMethod = Payment::where('lab_id', $labId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->pluck('total', 'payment_method');

        // Payment trend (simplified - show last 30 days)
        $paymentTrend = [];
        $current = Carbon::parse($startDate);
        $maxDays = 30;
        $dayIncrement = max(1, floor((Carbon::parse($endDate)->diffInDays($current)) / $maxDays));
        
        while ($current <= Carbon::parse($endDate)) {
            $amount = Payment::where('lab_id', $labId)
                ->whereDate('payment_date', $current->format('Y-m-d'))
                ->sum('amount');

            $paymentTrend[] = [
                'label' => $current->format('M d'),
                'amount' => (float) $amount,
            ];

            $current->addDays($dayIncrement);
            if ($current > Carbon::parse($endDate)) break;
        }

        return [
            'total' => $totalPayments,
            'by_method' => $paymentsByMethod,
            'trend' => $paymentTrend,
        ];
    }

    /**
     * Get appointment analytics.
     */
    private function getAppointmentAnalytics($labId, $startDate, $endDate)
    {
        // Total appointments
        $totalAppointments = Appointment::where('lab_id', $labId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->count();

        // Appointments by status
        $appointmentsByStatus = Appointment::where('lab_id', $labId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Appointments by source
        $appointmentsBySource = Appointment::where('lab_id', $labId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select('booking_source', DB::raw('COUNT(*) as count'))
            ->groupBy('booking_source')
            ->get()
            ->pluck('count', 'booking_source');

        return [
            'total' => $totalAppointments,
            'by_status' => $appointmentsByStatus,
            'by_source' => $appointmentsBySource,
        ];
    }

    /**
     * Get package statistics.
     */
    private function getPackageStatistics($labId, $startDate, $endDate)
    {
        // Top packages
        $topPackages = TestOrder::where('lab_id', $labId)
            ->whereNotNull('package_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('package_id', DB::raw('COUNT(*) as count'))
            ->with('package')
            ->groupBy('package_id')
            ->orderByDesc('count')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'name' => $order->package->name ?? 'Unknown',
                    'count' => $order->count,
                ];
            });

        // Total package orders
        $totalPackageOrders = TestOrder::where('lab_id', $labId)
            ->whereNotNull('package_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'total' => $totalPackageOrders,
            'top_packages' => $topPackages,
        ];
    }

    /**
     * Get date range based on period.
     */
    private function getDateRange($period)
    {
        $endDate = Carbon::today();
        
        switch ($period) {
            case 'day':
                $startDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::today()->subWeek();
                break;
            case 'month':
                $startDate = Carbon::today()->subMonth();
                break;
            case 'year':
                $startDate = Carbon::today()->subYear();
                break;
            default:
                $startDate = Carbon::today()->subMonth();
        }

        return [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
        ];
    }

    /**
     * Export analytics report to PDF/Excel.
     */
    public function export(Request $request)
    {
        // This can be implemented later with a PDF/Excel library
        // For now, return JSON data
        $labId = currentLabId();
        $period = $request->get('period', 'month');
        $dateRange = $this->getDateRange($period);
        
        $data = [
            'revenue' => $this->getRevenueAnalytics($labId, $dateRange['start'], $dateRange['end'], $period),
            'test_stats' => $this->getTestStatistics($labId, $dateRange['start'], $dateRange['end']),
            'patient_stats' => $this->getPatientAnalytics($labId, $dateRange['start'], $dateRange['end']),
        ];

        return response()->json($data);
    }
}
