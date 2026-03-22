<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestOrder extends Model
{
    protected $fillable = [
        'lab_id',
        'patient_id',
        'test_id',
        'package_id',
        'doctor_id',
        'referral_number',
        'status',
        'sample_status',
        'sample_collected_at',
        'collected_by',
        'technician_id',
        'report_status',
        'bill_id',
        'price',
        'discount',
        'final_price',
        'payment_status',
        'inventory_deducted',
    ];

    protected $casts = [
        'sample_collected_at' => 'datetime',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'inventory_deducted' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TestPackage::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }

    /**
     * Scope a query to only include test orders for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
