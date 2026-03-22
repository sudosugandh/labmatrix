<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAdjustment extends Model
{
    protected $fillable = [
        'lab_id',
        'item_id',
        'quantity',
        'adjustment_type',
        'note',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
