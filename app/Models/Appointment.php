<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
