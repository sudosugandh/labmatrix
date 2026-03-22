<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportTemplate extends Model
{
    protected $fillable = [
        'lab_id',
        'created_by',
        'name',
        'description',
        'report_type',
        'selected_fields',
        'filters',
        'sorting',
        'grouping',
        'formatting',
        'is_public',
        'is_default',
        'usage_count',
    ];

    protected $casts = [
        'selected_fields' => 'array',
        'filters' => 'array',
        'sorting' => 'array',
        'grouping' => 'array',
        'formatting' => 'array',
        'is_public' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the lab that owns the template.
     */
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /**
     * Get the user who created the template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get templates for current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    /**
     * Scope to get public templates.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to filter by report type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
