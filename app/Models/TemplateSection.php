<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateSection extends Model
{
    protected $fillable = [
        'template_id',
        'section_type',
        'title',
        'content_json',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'content_json' => 'array',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(DiagnosticReportTemplate::class, 'template_id', 'id');
    }

    /**
     * Get formatted content based on section type
     */
    public function getFormattedContent()
    {
        return $this->content_json ?? [];
    }

    /**
     * Check if section should display parameters
     */
    public function isParametersSection(): bool
    {
        return $this->section_type === 'parameters';
    }

    /**
     * Check if section should display calculated values
     */
    public function isCalculatedSection(): bool
    {
        return $this->section_type === 'calculated';
    }

    /**
     * Check if section should display interpretation
     */
    public function isInterpretationSection(): bool
    {
        return $this->section_type === 'interpretation';
    }
}
