<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'lab_id',
        'name',
        'phone',
        'email',
        'age',
        'gender',
        'address',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Scope a query to only include patients for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
