<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ScheduledReport extends Model
{
    protected $fillable = [
        'lab_id',
        'report_template_id',
        'created_by',
        'name',
        'description',
        'frequency',
        'schedule_config',
        'send_time',
        'recipients',
        'format',
        'is_active',
        'last_run_at',
        'next_run_at',
        'run_count',
    ];

    protected $casts = [
        'schedule_config' => 'array',
        'recipients' => 'array',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'send_time' => 'datetime',
    ];

    /**
     * Get the lab that owns the scheduled report.
     */
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /**
     * Get the report template.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    /**
     * Get the user who created the scheduled report.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get active scheduled reports.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get reports due to run.
     */
    public function scopeDueToRun($query)
    {
        return $query->where('is_active', true)
            ->where('next_run_at', '<=', now())
            ->orWhereNull('next_run_at');
    }

    /**
     * Calculate next run time based on frequency.
     */
    public function calculateNextRun(): Carbon
    {
        $now = now();
        $sendTime = Carbon::parse($this->send_time);
        
        switch ($this->frequency) {
            case 'daily':
                $next = $now->copy()->setTimeFromTimeString($sendTime->format('H:i:s'));
                if ($next->lte($now)) {
                    $next->addDay();
                }
                return $next;
                
            case 'weekly':
                $dayOfWeek = $this->schedule_config['day_of_week'] ?? 1; // Monday = 1
                $next = $now->copy()->next(Carbon::getDays()[$dayOfWeek - 1]);
                $next->setTimeFromTimeString($sendTime->format('H:i:s'));
                return $next;
                
            case 'monthly':
                $dayOfMonth = $this->schedule_config['day_of_month'] ?? 1;
                $next = $now->copy()->startOfMonth()->addDays($dayOfMonth - 1);
                $next->setTimeFromTimeString($sendTime->format('H:i:s'));
                if ($next->lte($now)) {
                    $next->addMonth();
                }
                return $next;
                
            default:
                return $now->addDay();
        }
    }

    /**
     * Mark as run and calculate next run.
     */
    public function markAsRun(): void
    {
        $this->update([
            'last_run_at' => now(),
            'next_run_at' => $this->calculateNextRun(),
            'run_count' => $this->run_count + 1,
        ]);
    }
}
