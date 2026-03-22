<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = [
        'lab_id',
        'name',
        'description',
        'image',
        'price',
        'report_template_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(TestPackage::class, 'package_tests', 'test_id', 'package_id')
            ->withTimestamps();
    }

    public function consumables(): HasMany
    {
        return $this->hasMany(TestConsumable::class);
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(TestParameter::class)->orderBy('sort_order');
    }

    public function reportTemplate(): BelongsTo
    {
        return $this->belongsTo(DiagnosticReportTemplate::class, 'report_template_id');
    }

    /**
     * Get the report template for this test, or default template
     */
    public function getReportTemplate()
    {
        if ($this->reportTemplate) {
            return $this->reportTemplate;
        }
        
        // Return default template for lab
        return DiagnosticReportTemplate::getDefaultForLab($this->lab_id);
    }

    /**
     * Scope a query to only include tests for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
