<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResult extends Model
{
    protected $fillable = [
        'lab_id',
        'test_order_id',
        'test_parameter_id',
        'parameter_name',
        'method_name',
        'result_value',
        'unit',
        'reference_range',
        'status',
        'remarks',
        'entered_by',
        'entered_at',
    ];

    protected $casts = [
        'entered_at' => 'datetime',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function testOrder(): BelongsTo
    {
        return $this->belongsTo(TestOrder::class);
    }

    public function testParameter(): BelongsTo
    {
        return $this->belongsTo(TestParameter::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function getStatusFlagAttribute(): string
    {
        return match($this->status) {
            'high' => 'H',
            'low' => 'L',
            'critical' => 'C',
            default => '',
        };
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
