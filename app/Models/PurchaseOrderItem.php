<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'inventory_item_id',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'discount',
        'tax_rate',
        'line_total',
        'notes',
    ];

    protected $casts = [
        'quantity_ordered' => 'decimal:2',
        'quantity_received' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function calculateLineTotal(): void
    {
        $subtotal = ($this->quantity_ordered * $this->unit_cost) - $this->discount;
        $tax = $subtotal * ($this->tax_rate / 100);
        $this->line_total = $subtotal + $tax;
    }
}
