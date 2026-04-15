<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    protected $fillable = [
        'lab_id',
        'domain',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /**
     * Resolve tenant lab id from request host (matches DetectDomain lookup).
     * Used as a fallback when rendering branded 404 so all tenant domains stay consistent.
     */
    public static function activeLabIdForHost(string $host): ?int
    {
        $hostWithoutPort = explode(':', $host, 2)[0];

        $labId = static::query()
            ->where(function ($query) use ($host, $hostWithoutPort) {
                $query->where('domain', $host)
                    ->orWhere('domain', $hostWithoutPort);
            })
            ->where('is_active', true)
            ->value('lab_id');

        return $labId !== null ? (int) $labId : null;
    }
}
