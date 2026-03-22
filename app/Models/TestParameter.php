<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestParameter extends Model
{
    protected $fillable = [
        'lab_id',
        'test_id',
        'parameter_name',
        'method_name',
        'unit',
        'reference_range_low',
        'reference_range_high',
        'sort_order',
    ];

    protected $casts = [
        'reference_range_low' => 'decimal:2',
        'reference_range_high' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get formatted reference range
     */
    public function getFormattedReferenceRangeAttribute(): string
    {
        if ($this->reference_range_low !== null && $this->reference_range_high !== null) {
            return $this->reference_range_low . ' - ' . $this->reference_range_high . ($this->unit ? ' ' . $this->unit : '');
        } elseif ($this->reference_range_low !== null) {
            return '≥ ' . $this->reference_range_low . ($this->unit ? ' ' . $this->unit : '');
        } elseif ($this->reference_range_high !== null) {
            return '≤ ' . $this->reference_range_high . ($this->unit ? ' ' . $this->unit : '');
        }
        return 'N/A';
    }

    /**
     * Scope a query to only include parameters for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
