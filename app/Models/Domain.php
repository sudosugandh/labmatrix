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
}
