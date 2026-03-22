<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\InventoryAdjustment;
use App\Models\TestOrder;
use App\Models\TestConsumable;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Deduct inventory when test is completed
     */
    public function deductInventoryForTest(TestOrder $testOrder): void
    {
        // Only deduct if test is completed and hasn't been deducted before
        if ($testOrder->status !== 'completed') {
            return;
        }

        // Check if inventory already deducted (prevent double deduction)
        if ($testOrder->inventory_deducted ?? false) {
            return;
        }

        DB::transaction(function () use ($testOrder) {
            $test = $testOrder->test;
            
            if (!$test) {
                return;
            }

            // Get consumables for this test
            $consumables = TestConsumable::where('lab_id', currentLabId())
                ->where('test_id', $test->id)
                ->with('item')
                ->get();

            foreach ($consumables as $consumable) {
                $item = $consumable->item;
                $quantityRequired = $consumable->quantity_required;

                if (!$item || $item->current_stock < $quantityRequired) {
                    \Log::warning("Insufficient stock for test order {$testOrder->id}. Item: {$item->name}, Required: {$quantityRequired}, Available: {$item->current_stock}");
                    continue;
                }

                // Deduct stock
                $item->decrement('current_stock', $quantityRequired);

                // Create adjustment record
                InventoryAdjustment::create([
                    'lab_id' => currentLabId(),
                    'item_id' => $item->id,
                    'quantity' => -$quantityRequired,
                    'adjustment_type' => 'remove',
                    'note' => "Auto-deducted for test order #{$testOrder->id} - {$test->name}",
                    'created_by' => auth()->id(),
                ]);

                // Check for alerts
                $this->checkAndCreateAlerts($item);
            }

            // Mark inventory as deducted
            $testOrder->update(['inventory_deducted' => true]);
        });
    }

    /**
     * Add stock adjustment
     */
    public function addStock(InventoryItem $item, float $quantity, string $note = null, string $type = 'add'): InventoryAdjustment
    {
        return DB::transaction(function () use ($item, $quantity, $note, $type) {
            $item->increment('current_stock', $quantity);

            $adjustment = InventoryAdjustment::create([
                'lab_id' => currentLabId(),
                'item_id' => $item->id,
                'quantity' => $quantity,
                'adjustment_type' => $type,
                'note' => $note ?? 'Stock added',
                'created_by' => auth()->id(),
            ]);

            $this->checkAndCreateAlerts($item);

            return $adjustment;
        });
    }

    /**
     * Remove stock adjustment
     */
    public function removeStock(InventoryItem $item, float $quantity, string $note = null, string $type = 'remove'): InventoryAdjustment
    {
        return DB::transaction(function () use ($item, $quantity, $note, $type) {
            if ($item->current_stock < $quantity) {
                throw new \Exception("Insufficient stock. Available: {$item->current_stock}");
            }

            $item->decrement('current_stock', $quantity);

            $adjustment = InventoryAdjustment::create([
                'lab_id' => currentLabId(),
                'item_id' => $item->id,
                'quantity' => -$quantity,
                'adjustment_type' => $type,
                'note' => $note ?? 'Stock removed',
                'created_by' => auth()->id(),
            ]);

            $this->checkAndCreateAlerts($item);

            return $adjustment;
        });
    }

    /**
     * Adjust stock (correction)
     */
    public function adjustStock(InventoryItem $item, float $newQuantity, string $note = null): InventoryAdjustment
    {
        return DB::transaction(function () use ($item, $newQuantity, $note) {
            $difference = $newQuantity - $item->current_stock;
            $item->update(['current_stock' => $newQuantity]);

            $adjustment = InventoryAdjustment::create([
                'lab_id' => currentLabId(),
                'item_id' => $item->id,
                'quantity' => abs($difference),
                'adjustment_type' => $difference > 0 ? 'add' : 'remove',
                'note' => $note ?? 'Stock correction',
                'created_by' => auth()->id(),
            ]);

            $this->checkAndCreateAlerts($item);

            return $adjustment;
        });
    }

    /**
     * Check and create stock alerts
     */
    protected function checkAndCreateAlerts(InventoryItem $item): void
    {
        try {
            $settings = \App\Models\WebsiteSetting::getForCurrentLab();
            
            // Only send alerts if inventory alerts are enabled
            if (!($settings->alert_inventory ?? true)) {
                return;
            }

            $alertService = app(\App\Services\AlertService::class);
            
            // Check for different alert types
            if ($item->isOutOfStock()) {
                $alertService->sendInventoryAlert($item, 'out_of_stock');
            } elseif ($item->isLowStock()) {
                $alertService->sendInventoryAlert($item, 'low_stock');
            }
            
            if ($item->expiry_date) {
                if ($item->isExpired()) {
                    $alertService->sendInventoryAlert($item, 'expired');
                } elseif ($item->isExpiringSoon()) {
                    $alertService->sendInventoryAlert($item, 'expiring_soon');
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send inventory alert: ' . $e->getMessage());
        }
    }

    /**
     * Get stock alerts for dashboard
     */
    public function getStockAlerts(): array
    {
        $labId = currentLabId();
        
        $lowStock = InventoryItem::where('lab_id', $labId)
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->where('current_stock', '>', 0)
            ->get();

        $outOfStock = InventoryItem::where('lab_id', $labId)
            ->where('current_stock', '<=', 0)
            ->get();

        $expiringSoon = InventoryItem::where('lab_id', $labId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->get();

        $expired = InventoryItem::where('lab_id', $labId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->get();

        return [
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'expiring_soon' => $expiringSoon,
            'expired' => $expired,
        ];
    }
}
