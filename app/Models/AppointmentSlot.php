<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentSlot extends Model
{
    protected $fillable = [
        'lab_id',
        'weekday',
        'start_time',
        'max_bookings',
        'label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Human-readable time for display (e.g. 9:00 AM).
     */
    public function getDisplayTimeAttribute(): string
    {
        $raw = $this->attributes['start_time'] ?? '00:00:00';

        return \Carbon\Carbon::parse($raw)->format('g:i A');
    }

    /**
     * Time as H:i for forms and API.
     */
    public function getTimeStringAttribute(): string
    {
        $raw = $this->attributes['start_time'] ?? '00:00:00';

        return \Carbon\Carbon::parse($raw)->format('H:i');
    }

    public static function weekdayLabels(): array
    {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
    }
}
