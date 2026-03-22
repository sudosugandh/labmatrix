<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiagnosticReportTemplate extends Model
{
    protected $table = 'diagnostic_report_templates';
    
    protected $fillable = [
        'lab_id',
        'name',
        'description',
        'template_type',
        'layout_type',
        'is_default',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class, 'template_id', 'id')->orderBy('sort_order');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class, 'report_template_id');
    }

    /**
     * Scope a query to only include templates for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    /**
     * Get the default template for a lab
     */
    public static function getDefaultForLab($labId)
    {
        return self::where('lab_id', $labId)
            ->where('is_default', true)
            ->first();
    }
}
