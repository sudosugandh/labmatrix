<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'lab_id',
        'name',
        'sku',
        'unit',
        'current_stock',
        'minimum_stock',
        'cost',
        'expiry_date',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'cost' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(InventoryAdjustment::class, 'item_id');
    }

    public function testConsumables(): HasMany
    {
        return $this->hasMany(TestConsumable::class, 'item_id');
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock')
            ->where('current_stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock && $this->current_stock > 0;
    }

    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date <= now()->addDays($days) && $this->expiry_date > now();
    }

    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date < now();
    }
}
