<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestPackage extends Model
{
    protected $fillable = [
        'lab_id',
        'name',
        'description',
        'price',
        'discount',
        'final_price',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'package_tests', 'package_id', 'test_id')
            ->withTimestamps();
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calculate final price based on discount
     */
    public function calculateFinalPrice(): float
    {
        if ($this->discount > 0) {
            return $this->price - ($this->price * $this->discount / 100);
        }
        return $this->price;
    }
}
