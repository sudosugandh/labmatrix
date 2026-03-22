<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'lab_id',
        'inventory_item_id',
        'purchase_order_id',
        'type',
        'quantity',
        'unit_cost',
        'reference_number',
        'reason',
        'created_by',
        'movement_date',
        'stock_before',
        'stock_after',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'stock_before' => 'decimal:2',
        'stock_after' => 'decimal:2',
        'movement_date' => 'datetime',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function scopeIncoming($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('type', 'out');
    }
}
