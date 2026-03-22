<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'lab_id',
        'po_number',
        'supplier_id',
        'created_by',
        'order_date',
        'expected_delivery_date',
        'received_date',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'discount',
        'total_amount',
        'notes',
        'terms',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'received_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function calculateTotal(): void
    {
        $this->subtotal = $this->items->sum('line_total');
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_cost - $this->discount;
    }

    public static function generatePONumber(): string
    {
        $labId = currentLabId();
        $year = now()->format('Y');
        $lastPO = static::where('lab_id', $labId)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastPO ? ((int) substr($lastPO->po_number, -4)) + 1 : 1;
        
        return 'PO-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
