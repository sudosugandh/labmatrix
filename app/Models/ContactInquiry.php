<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInquiry extends Model
{
    protected $fillable = [
        'lab_id',
        'name',
        'phone',
        'message',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }
}
