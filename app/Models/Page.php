<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'lab_id',
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'status',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /**
     * Generate slug from title if not provided.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    /**
     * Scope a query to only include pages for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
