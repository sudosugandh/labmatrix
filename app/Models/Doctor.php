<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $fillable = [
        'lab_id',
        'name',
        'specialization',
        'qualification',
        'phone',
        'email',
        'clinic_name',
        'address',
        'registration_number',
        'is_active',
        'commission_type',
        'commission_rate',
        'total_commission_earned',
        'total_commission_paid',
        'pending_commission',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
        'total_commission_earned' => 'decimal:2',
        'total_commission_paid' => 'decimal:2',
        'pending_commission' => 'decimal:2',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(DoctorCommission::class);
    }

    /**
     * Calculate commission for a given amount.
     */
    public function calculateCommission(float $amount): float
    {
        if ($this->commission_type === 'percentage') {
            return ($amount * $this->commission_rate) / 100;
        } else {
            return $this->commission_rate; // Fixed amount
        }
    }

    /**
     * Scope a query to only include doctors for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
