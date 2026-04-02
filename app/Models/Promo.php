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
        'valid_from',
        'valid_until',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
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
        $today = now()->toDateString();

        return $query->where(function ($q) use ($today) {
            $q->where(function ($q2) use ($today) {
                $q2->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
            })->where(function ($q3) use ($today) {
                $q3->whereNull('valid_until')->orWhere('valid_until', '>=', $today);
            });
        });
    }
}
