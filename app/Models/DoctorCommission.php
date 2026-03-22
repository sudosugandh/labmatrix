<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorCommission extends Model
{
    protected $fillable = [
        'lab_id',
        'doctor_id',
        'test_order_id',
        'bill_id',
        'order_amount',
        'commission_amount',
        'status',
        'commission_date',
        'payment_date',
        'notes',
        'paid_by',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_date' => 'date',
        'payment_date' => 'date',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function testOrder(): BelongsTo
    {
        return $this->belongsTo(TestOrder::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }
}
