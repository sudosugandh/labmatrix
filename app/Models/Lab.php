<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lab extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'level',
        'status',
        'subscription_plan',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'monthly_fee',
        'billing_notes',
    ];

    protected $casts = [
        'level' => 'string',
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'monthly_fee' => 'decimal:2',
    ];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function supportNotes(): HasMany
    {
        return $this->hasMany(\App\Models\LabSupportNote::class);
    }
}
