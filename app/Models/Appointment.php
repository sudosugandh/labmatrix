<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\TestPackage;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'patient_id',
        'doctor_id',
        'referring_doctor_name',
        'referring_doctor_phone',
        'prescription_file',
        'package_id',
        'appointment_date',
        'appointment_time',
        'appointment_slot_id',
        'status',
        'booking_source',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'string', // Store as time string, not datetime
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TestPackage::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointmentSlot(): BelongsTo
    {
        return $this->belongsTo(AppointmentSlot::class);
    }

    /**
     * Tests selected at booking (individual tests) or synced from package.
     */
    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'appointment_test')->withTimestamps();
    }

    public function bill(): HasOne
    {
        return $this->hasOne(Bill::class, 'appointment_id');
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    /**
     * Scope a query to only include appointments for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'bg-blue-100 text-blue-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'no_show' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * When the appointment slot starts (date + time in app timezone).
     */
    public function startsAt(): Carbon
    {
        $timeStr = strlen((string) $this->appointment_time) === 5
            ? $this->appointment_time . ':00'
            : $this->appointment_time;

        return Carbon::parse($this->appointment_date->format('Y-m-d') . ' ' . $timeStr);
    }

    /**
     * Public portal: patient may cancel only upcoming bookings (not after visit time has passed).
     */
    public function canBeCancelledByPatient(): bool
    {
        if (! in_array($this->status, ['scheduled', 'confirmed'], true)) {
            return false;
        }
        if ($this->booking_source !== 'public') {
            return false;
        }

        return $this->startsAt()->isFuture();
    }
}
