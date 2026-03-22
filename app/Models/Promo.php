<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    protected $fillable = [
        'lab_id',
        'title',
        'short_text',
        'link',
        'valid_until',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()->toDateString());
        });
    }
}
