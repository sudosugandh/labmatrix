<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'lab_id',
        'bill_id',
        'amount',
        'payment_method',
        'transaction_id',
        'cheque_number',
        'payment_date',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Scope a query to only include payments for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
