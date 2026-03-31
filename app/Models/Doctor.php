<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Doctor extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'lab_id',
        'name',
        'specialization',
        'qualification',
        'phone',
        'email',
        'password',
        'clinic_name',
        'address',
        'registration_number',
        'is_active',
        'commission_type',
        'commission_rate',
        'total_commission_earned',
        'total_commission_paid',
        'pending_commission',
        'referral_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
        'total_commission_earned' => 'decimal:2',
        'total_commission_paid' => 'decimal:2',
        'pending_commission' => 'decimal:2',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        static::creating(function (Doctor $doctor) {
            if (empty($doctor->referral_code) && $doctor->lab_id) {
                $doctor->referral_code = static::generateReferralCode((int) $doctor->lab_id);
            }
        });
    }

    public static function generateReferralCode(int $labId): string
    {
        do {
            $code = strtolower(Str::random(8));
        } while (static::where('lab_id', $labId)->where('referral_code', $code)->exists());

        return $code;
    }

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
        }

        return (float) $this->commission_rate;
    }

    /**
     * Scope a query to only include doctors for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    /**
     * Public referral link for this lab (uses DetectDomain / APP_URL).
     */
    public function referralBookingUrl(): string
    {
        return url('/book-appointment?type=patient&ref='.$this->referral_code);
    }
}
