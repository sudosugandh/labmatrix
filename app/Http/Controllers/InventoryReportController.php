<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryAdjustment;
use App\Models\TestConsumable;
use App\Models\TestOrder;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryReportController extends Controller
{
    /**
     * Display inventory reports dashboard
     */
    public function index(): View
    {
        $labId = currentLabId();
        
        // Quick stats
        $stats = [
            'total_items' => InventoryItem::where('lab_id', $labId)->count(),
            'total_adjustments' => InventoryAdjustment::where('lab_id', $labId)->count(),
            'total_consumption' => abs(InventoryAdjustment::where('lab_id', $labId)
                ->where('adjustment_type', 'remove')
                ->sum('quantity')),
            'total_value' => InventoryItem::where('lab_id', $labId)
                ->sum(DB::raw('current_stock * cost')),
        ];

        return view('inventory.reports.index', compact('stats'));
    }

    /**
     * Usage Report - Most consumed items
     */
    public function usage(Request $request): View
    {
        $labId = currentLabId();
        $period = $request->get('period', '30'); // days
        
        $startDate = now()->subDays($period);
        
        // Get consumption from adjustments (removals)
        $usage = InventoryAdjustment::where('lab_id', $labId)
            ->where('adjustment_type', 'remove')
            ->where('created_at', '>=', $startDate)
            ->select('item_id', DB::raw('SUM(ABS(quantity)) as total_consumed'))
            ->groupBy('item_id')
            ->with('item')
            ->orderByDesc('total_consumed')
            ->get();

        // Calculate consumption rate (per day)
        $usage = $usage->map(function ($item) use ($period) {
            $item->consumption_rate = $item->total_consumed / $period;
            $item->days_until_out = $item->item->current_stock > 0 
                ? round($item->item->current_stock / $item->consumption_rate, 1)
                : 0;
            return $item;
        });

        return view('inventory.reports.usage', compact('usage', 'period'));
    }

    /**
     * Consumption Trends - Monthly/Weekly patterns
     */
    public function trends(Request $request): View
    {
        $labId = currentLabId();
        $type = $request->get('type', 'monthly'); // monthly or weekly
        
        if ($type === 'monthly') {
            $data = $this->getMonthlyTrends($labId);
            $periodLabel = 'Last 12 Months';
        } else {
            $data = $this->getWeeklyTrends($labId);
            $periodLabel = 'Last 12 Weeks';
        }

        return view('inventory.reports.trends', compact('data', 'type', 'periodLabel'));
    }

    /**
     * Cost Analysis - Cost per test
     */
    public function costAnalysis(Request $request): View
    {
        $labId = currentLabId();
        
        // Get all tests with their consumables
        $tests = Test::where('lab_id', $labId)
            ->with(['consumables.item'])
            ->get();

        $testCosts = $tests->map(function ($test) {
            $totalCost = 0;
            $consumables = [];
            
            foreach ($test->consumables as $consumable) {
                $itemCost = $consumable->item->cost * $consumable->quantity_required;
                $totalCost += $itemCost;
                
                $consumables[] = [
                    'item' => $consumable->item->name,
                    'quantity' => $consumable->quantity_required,
                    'unit_cost' => $consumable->item->cost,
                    'total_cost' => $itemCost,
                ];
            }
            
            return [
                'test' => $test,
                'total_cost' => $totalCost,
                'profit_margin' => $test->price > 0 ? (($test->price - $totalCost) / $test->price) * 100 : 0,
                'consumables' => $consumables,
            ];
        })->sortByDesc('total_cost');

        return view('inventory.reports.cost-analysis', compact('testCosts'));
    }

    /**
     * Reorder Recommendations
     */
    public function reorderRecommendations(): View
    {
        $labId = currentLabId();
        
        // Get items that need reordering
        $items = InventoryItem::where('lab_id', $labId)
            ->where(function($query) {
                $query->whereColumn('current_stock', '<=', 'minimum_stock')
                      ->orWhere('current_stock', '<=', 0);
            })
            ->get();

        $recommendations = $items->map(function ($item) use ($labId) {
            // Calculate average consumption rate (last 30 days)
            $consumption = abs(InventoryAdjustment::where('lab_id', $labId)
                ->where('item_id', $item->id)
                ->where('adjustment_type', 'remove')
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('quantity'));

            $dailyConsumption = $consumption / 30;
            
            // Calculate days until out of stock
            $daysUntilOut = $dailyConsumption > 0 
                ? round($item->current_stock / $dailyConsumption, 1)
                : 999;

            // Recommended reorder quantity (enough for 60 days)
            $recommendedQuantity = max(
                $item->minimum_stock * 2,
                $dailyConsumption * 60
            );

            return [
                'item' => $item,
                'current_stock' => $item->current_stock,
                'minimum_stock' => $item->minimum_stock,
                'daily_consumption' => round($dailyConsumption, 2),
                'days_until_out' => $daysUntilOut,
                'recommended_quantity' => round($recommendedQuantity, 0),
                'estimated_cost' => round($recommendedQuantity * $item->cost, 2),
                'priority' => $item->current_stock <= 0 ? 'critical' : ($daysUntilOut < 7 ? 'high' : 'medium'),
            ];
        })->sortBy(function($item) {
            return $item['priority'] === 'critical' ? 0 : ($item['priority'] === 'high' ? 1 : 2);
        });

        return view('inventory.reports.reorder', compact('recommendations'));
    }

    /**
     * Expiry Tracking Report
     */
    public function expiryTracking(Request $request): View
    {
        $labId = currentLabId();
        $days = $request->get('days', 90); // Look ahead days
        
        $items = InventoryItem::where('lab_id', $labId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->orderBy('expiry_date')
            ->get();

        $items = $items->map(function ($item) {
            $daysUntilExpiry = now()->diffInDays($item->expiry_date, false);
            
            return [
                'item' => $item,
                'expiry_date' => $item->expiry_date,
                'days_until_expiry' => $daysUntilExpiry,
                'status' => $daysUntilExpiry < 0 ? 'expired' : ($daysUntilExpiry <= 30 ? 'critical' : 'warning'),
                'stock_value' => $item->current_stock * $item->cost,
            ];
        });

        return view('inventory.reports.expiry', compact('items', 'days'));
    }

    /**
     * Get monthly consumption trends
     */
    private function getMonthlyTrends($labId): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            
            $consumption = abs(InventoryAdjustment::where('lab_id', $labId)
                ->where('adjustment_type', 'remove')
                ->whereBetween('created_at', [$start, $end])
                ->sum('quantity'));

            $data[] = [
                'period' => $month->format('M Y'),
                'consumption' => $consumption,
            ];
        }
        
        return $data;
    }

    /**
     * Get weekly consumption trends
     */
    private function getWeeklyTrends($labId): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $week = now()->subWeeks($i);
            $start = $week->copy()->startOfWeek();
            $end = $week->copy()->endOfWeek();
            
            $consumption = abs(InventoryAdjustment::where('lab_id', $labId)
                ->where('adjustment_type', 'remove')
                ->whereBetween('created_at', [$start, $end])
                ->sum('quantity'));

            $data[] = [
                'period' => 'Week ' . $week->format('W, Y'),
                'consumption' => $consumption,
            ];
        }
        
        return $data;
    }

    /**
     * Export report to CSV
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'usage');
        $labId = currentLabId();
        
        $filename = "inventory_report_{$type}_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($type, $labId) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'usage':
                    $this->exportUsageReport($file, $labId);
                    break;
                case 'reorder':
                    $this->exportReorderReport($file, $labId);
                    break;
                case 'expiry':
                    $this->exportExpiryReport($file, $labId);
                    break;
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportUsageReport($file, $labId)
    {
        fputcsv($file, ['Item Name', 'SKU', 'Total Consumed', 'Consumption Rate/Day', 'Days Until Out']);
        
        $usage = InventoryAdjustment::where('lab_id', $labId)
            ->where('adjustment_type', 'remove')
            ->where('created_at', '>=', now()->subDays(30))
            ->select('item_id', DB::raw('SUM(ABS(quantity)) as total_consumed'))
            ->groupBy('item_id')
            ->with('item')
            ->get();

        foreach ($usage as $item) {
            $consumptionRate = $item->total_consumed / 30;
            $daysUntilOut = $item->item->current_stock > 0 
                ? round($item->item->current_stock / $consumptionRate, 1)
                : 0;
            
            fputcsv($file, [
                $item->item->name,
                $item->item->sku ?? 'N/A',
                $item->total_consumed,
                round($consumptionRate, 2),
                $daysUntilOut,
            ]);
        }
    }

    private function exportReorderReport($file, $labId)
    {
        fputcsv($file, ['Item Name', 'Current Stock', 'Minimum Stock', 'Daily Consumption', 'Days Until Out', 'Recommended Quantity', 'Estimated Cost']);
        
        $items = InventoryItem::where('lab_id', $labId)
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->get();

        foreach ($items as $item) {
            $consumption = abs(InventoryAdjustment::where('lab_id', $labId)
                ->where('item_id', $item->id)
                ->where('adjustment_type', 'remove')
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('quantity'));

            $dailyConsumption = $consumption / 30;
            $daysUntilOut = $dailyConsumption > 0 ? round($item->current_stock / $dailyConsumption, 1) : 999;
            $recommendedQuantity = max($item->minimum_stock * 2, $dailyConsumption * 60);
            
            fputcsv($file, [
                $item->name,
                $item->current_stock,
                $item->minimum_stock,
                round($dailyConsumption, 2),
                $daysUntilOut,
                round($recommendedQuantity, 0),
                round($recommendedQuantity * $item->cost, 2),
            ]);
        }
    }

    private function exportExpiryReport($file, $labId)
    {
        fputcsv($file, ['Item Name', 'SKU', 'Current Stock', 'Expiry Date', 'Days Until Expiry', 'Stock Value']);
        
        $items = InventoryItem::where('lab_id', $labId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->orderBy('expiry_date')
            ->get();

        foreach ($items as $item) {
            $daysUntilExpiry = now()->diffInDays($item->expiry_date, false);
            
            fputcsv($file, [
                $item->name,
                $item->sku ?? 'N/A',
                $item->current_stock,
                $item->expiry_date->format('Y-m-d'),
                $daysUntilExpiry,
                round($item->current_stock * $item->cost, 2),
            ]);
        }
    }
}
