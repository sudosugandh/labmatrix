<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabSupportNote extends Model
{
    protected $fillable = [
        'lab_id',
        'created_by',
        'note',
        'type',
        'is_important',
    ];

    protected $casts = [
        'is_important' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
