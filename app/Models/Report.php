<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'lab_id',
        'patient_id',
        'test_order_id',
        'report_number',
        'report_name',
        'report_file',
        'remarks',
        'status',
        'workflow_status',
        'interpretation',
        'reviewed_at',
        'reviewed_by',
        'released_at',
        'released_by',
        'doctor_name',
        'doctor_qualification',
        'doctor_signature',
        'uploaded_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function testOrder(): BelongsTo
    {
        return $this->belongsTo(TestOrder::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function releasedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function reportTests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReportTest::class)->orderBy('sort_order');
    }

    /**
     * Scope a query to only include reports for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    /**
     * Scope a query to only include released reports.
     */
    public function scopeReleased($query)
    {
        return $query->where('workflow_status', 'released');
    }
}
