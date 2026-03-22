<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlert extends Model
{
    protected $fillable = [
        'lab_id',
        'inventory_item_id',
        'alert_type',
        'current_stock',
        'threshold',
        'expiry_date',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'threshold' => 'decimal:2',
        'expiry_date' => 'date',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('alert_type', $type);
    }

    public function resolve(int $userId, ?string $notes = null): void
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId,
            'resolution_notes' => $notes,
        ]);
    }
}
