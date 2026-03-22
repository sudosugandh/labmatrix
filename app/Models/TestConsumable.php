<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestConsumable extends Model
{
    protected $fillable = [
        'lab_id',
        'test_id',
        'item_id',
        'quantity_required',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:2',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
