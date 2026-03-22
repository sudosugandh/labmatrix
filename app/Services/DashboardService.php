<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\TestOrder;
use App\Models\Report;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Doctor;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    protected $labId;
    protected $cachePrefix = 'dashboard_stats_';

    public function __construct()
    {
        $this->labId = currentLabId();
    }

    /**
     * Get all dashboard statistics with caching
     */
    public function getStatistics(): array
    {
        return Cache::remember(
            $this->cachePrefix . $this->labId,
            now()->addMinutes(5), // Cache for 5 minutes
            function () {
                return [
                    'overview' => $this->getOverviewStats(),
                    'revenue' => $this->getRevenueStats(),
                    'appointments' => $this->getAppointmentStats(),
                    'tests' => $this->getTestStats(),
                    'trends' => $this->getTrendStats(),
                    'recent_activity' => $this->getRecentActivity(),
                    'stock_alerts' => $this->getStockAlerts(),
                ];
            }
        );
    }

    /**
     * Get overview statistics
     */
    protected function getOverviewStats(): array
    {
        $today = today();
        $yesterday = today()->copy()->subDay();
        $lastWeek = today()->copy()->subWeek();
        
        // Get patient counts for comparison
        $patientsToday = Patient::where('lab_id', $this->labId)
            ->whereDate('created_at', $today)
            ->count();
        $patientsYesterday = Patient::where('lab_id', $this->labId)
            ->whereDate('created_at', $yesterday)
            ->count();
        
        return [
            'total_patients' => [
                'value' => Patient::where('lab_id', $this->labId)->count(),
                'change' => $this->getChangePercentage($patientsToday, $patientsYesterday),
                'icon' => 'users',
                'color' => 'blue',
            ],
            'today_appointments' => [
                'value' => Appointment::where('lab_id', $this->labId)
                    ->whereDate('appointment_date', $today)
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count(),
                'change' => $this->getChangePercentage(
                    Appointment::where('lab_id', $this->labId)
                        ->whereDate('appointment_date', $today)
                        ->whereIn('status', ['scheduled', 'confirmed'])
                        ->count(),
                    Appointment::where('lab_id', $this->labId)
                        ->whereDate('appointment_date', $yesterday)
                        ->whereIn('status', ['scheduled', 'confirmed'])
                        ->count()
                ),
                'icon' => 'calendar',
                'color' => 'emerald',
            ],
            'pending_samples' => [
                'value' => TestOrder::where('lab_id', $this->labId)
                    ->where('sample_status', 'pending')
                    ->count(),
                'change' => null,
                'icon' => 'flask',
                'color' => 'amber',
            ],
            'tests_processing' => [
                'value' => TestOrder::where('lab_id', $this->labId)
                    ->where('status', 'processing')
                    ->count(),
                'change' => null,
                'icon' => 'cog',
                'color' => 'indigo',
            ],
            'reports_ready' => [
                'value' => Report::where('lab_id', $this->labId)
                    ->where('status', 'ready')
                    ->count(),
                'change' => null,
                'icon' => 'document',
                'color' => 'purple',
            ],
            'total_revenue' => [
                'value' => Payment::where('lab_id', $this->labId)
                    ->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year)
                    ->sum('amount'),
                'change' => $this->getRevenueChangePercentage(),
                'icon' => 'currency',
                'color' => 'green',
            ],
        ];
    }

    /**
     * Get revenue statistics
     */
    protected function getRevenueStats(): array
    {
        $today = today();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfLastMonth = $today->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $today->copy()->subMonth()->endOfMonth();

        return [
            'today' => Payment::where('lab_id', $this->labId)
                ->whereDate('payment_date', $today)
                ->sum('amount'),
            'this_month' => Payment::where('lab_id', $this->labId)
                ->whereBetween('payment_date', [$startOfMonth, $today])
                ->sum('amount'),
            'last_month' => Payment::where('lab_id', $this->labId)
                ->whereBetween('payment_date', [$startOfLastMonth, $endOfLastMonth])
                ->sum('amount'),
            'pending' => Bill::where('lab_id', $this->labId)
                ->where('status', '!=', 'paid')
                ->sum('due_amount'),
        ];
    }

    /**
     * Get appointment statistics
     */
    protected function getAppointmentStats(): array
    {
        $today = today();
        $thisWeek = $today->copy()->startOfWeek();
        $thisMonth = $today->copy()->startOfMonth();

        return [
            'today' => Appointment::where('lab_id', $this->labId)
                ->whereDate('appointment_date', $today)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count(),
            'this_week' => Appointment::where('lab_id', $this->labId)
                ->whereBetween('appointment_date', [$thisWeek, $today])
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count(),
            'this_month' => Appointment::where('lab_id', $this->labId)
                ->whereBetween('appointment_date', [$thisMonth, $today])
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count(),
            'upcoming' => Appointment::where('lab_id', $this->labId)
                ->where('appointment_date', '>', $today)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count(),
        ];
    }

    /**
     * Get test statistics
     */
    protected function getTestStats(): array
    {
        return [
            'pending' => TestOrder::where('lab_id', $this->labId)
                ->where('sample_status', 'pending')
                ->count(),
            'processing' => TestOrder::where('lab_id', $this->labId)
                ->where('status', 'processing')
                ->count(),
            'completed' => TestOrder::where('lab_id', $this->labId)
                ->where('status', 'completed')
                ->count(),
            'reports_ready' => Report::where('lab_id', $this->labId)
                ->where('status', 'ready')
                ->count(),
        ];
    }

    /**
     * Get trend statistics (last 7 days)
     */
    protected function getTrendStats(): array
    {
        $days = [];
        $appointments = [];
        $revenue = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $days[] = $date->format('M d');
            
            $appointments[] = Appointment::where('lab_id', $this->labId)
                ->whereDate('appointment_date', $date)
                ->whereIn('status', ['scheduled', 'confirmed', 'completed'])
                ->count();
            
            $revenue[] = Payment::where('lab_id', $this->labId)
                ->whereDate('payment_date', $date)
                ->sum('amount');
        }

        return [
            'days' => $days,
            'appointments' => $appointments,
            'revenue' => $revenue,
        ];
    }

    /**
     * Get recent activity
     */
    protected function getRecentActivity(): array
    {
        $recentOrders = TestOrder::where('lab_id', $this->labId)
            ->with(['patient', 'test'])
            ->latest()
            ->take(5)
            ->get();

        $recentAppointments = Appointment::where('lab_id', $this->labId)
            ->with(['patient', 'package'])
            ->latest()
            ->take(5)
            ->get();

        return [
            'orders' => $recentOrders,
            'appointments' => $recentAppointments,
        ];
    }

    /**
     * Calculate percentage change
     */
    protected function getChangePercentage($current, $previous): ?float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Get revenue change percentage
     */
    protected function getRevenueChangePercentage(): ?float
    {
        $thisMonth = Payment::where('lab_id', $this->labId)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $lastMonth = Payment::where('lab_id', $this->labId)
            ->whereMonth('payment_date', now()->subMonth()->month)
            ->whereYear('payment_date', now()->subMonth()->year)
            ->sum('amount');

        return $this->getChangePercentage($thisMonth, $lastMonth);
    }

    /**
     * Get stock alerts for dashboard
     */
    protected function getStockAlerts(): array
    {
        $inventoryService = app(InventoryService::class);
        return $inventoryService->getStockAlerts();
    }

    /**
     * Clear dashboard cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cachePrefix . $this->labId);
    }
}
