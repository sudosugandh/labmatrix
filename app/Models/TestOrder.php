<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TestOrder extends Model
{
    protected $fillable = [
        'lab_id',
        'patient_id',
        'appointment_id',
        'health_camp_id',
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

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function healthCamp(): BelongsTo
    {
        return $this->belongsTo(HealthCamp::class);
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

    /**
     * One-line status for patients on the public "Your reports" portal.
     */
    public function publicStatusLine(): string
    {
        $this->loadMissing('report');

        $report = $this->report;
        if ($report) {
            if ($report->workflow_status === 'released' && $report->status === 'ready') {
                return 'Report ready — PDF available.';
            }
            if ($report->workflow_status === 'released') {
                return 'Report released — file is being finalized.';
            }
            if ($report->workflow_status === 'reviewed') {
                return 'Report reviewed — pending final release by the lab.';
            }

            return 'Report is being prepared.';
        }

        return match ($this->status ?? 'pending') {
            'pending' => 'Order confirmed — awaiting sample collection.',
            'sample_collected' => 'Sample collected — processing at the lab.',
            'processing' => 'Sample processing in progress.',
            'completed' => 'Lab processing complete — your report is being prepared.',
            'report_uploaded' => 'Report received — processing.',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    /**
     * Status line for doctor portal referrals: reflects test order workflow, appointment, and report flags (admin updates).
     */
    public function doctorPortalStatusLabel(): string
    {
        $this->loadMissing('appointment');

        $main = match ($this->status ?? 'pending') {
            'pending' => 'Pending',
            'sample_collected' => 'Sample collected',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'report_uploaded' => 'Report uploaded',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };

        $parts = [$main];

        if ($this->appointment) {
            $visit = match ($this->appointment->status) {
                'scheduled' => 'Visit scheduled',
                'confirmed' => 'Visit confirmed',
                'completed' => 'Visit completed',
                'cancelled' => 'Visit cancelled',
                'no_show' => 'No-show',
                default => 'Visit '.ucfirst(str_replace('_', ' ', (string) $this->appointment->status)),
            };
            $parts[] = $visit;
        }

        $rs = $this->report_status ?? 'pending';
        if ($rs !== '' && $rs !== 'pending') {
            $parts[] = 'Report '.ucfirst(str_replace('_', ' ', (string) $rs));
        }

        return implode(' · ', $parts);
    }
}
