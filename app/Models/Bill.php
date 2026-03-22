<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    protected $fillable = [
        'lab_id',
        'patient_id',
        'bill_number',
        'payment_token',
        'bill_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'due_amount',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    /**
     * Generate unique bill number
     */
    public static function generateBillNumber(): string
    {
        $labId = currentLabId();
        $prefix = 'BILL-' . str_pad($labId, 3, '0', STR_PAD_LEFT) . '-';
        $lastBill = self::where('lab_id', $labId)
            ->where('bill_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastBill) {
            $lastNumber = (int) substr($lastBill->bill_number, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(): void
    {
        $oldStatus = $this->status;
        $totalPaid = $this->payments()->sum('amount');
        $this->paid_amount = $totalPaid;
        $this->due_amount = max(0, $this->total - $totalPaid);

        if ($this->due_amount <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'pending';
        }

        $this->save();

        // Calculate commissions when bill is fully paid
        if ($this->status === 'paid' && $oldStatus !== 'paid') {
            $this->calculateDoctorCommissions();
        }
    }

    /**
     * Calculate and create doctor commissions for this bill.
     */
    public function calculateDoctorCommissions(): void
    {
        $testOrders = $this->testOrders()->with(['doctor', 'test', 'package'])->get();
        
        // Group orders by doctor
        $doctorOrders = $testOrders->filter(fn($order) => $order->doctor_id)
            ->groupBy('doctor_id');

        foreach ($doctorOrders as $doctorId => $orders) {
            $doctor = $orders->first()->doctor;
            if (!$doctor || !$doctor->is_active) {
                continue;
            }

            // Calculate total order amount for this doctor from this bill
            $orderAmount = $orders->sum(function ($order) {
                return $order->package_id 
                    ? $order->package->final_price 
                    : ($order->final_price > 0 ? $order->final_price : $order->test->price);
            });

            // Calculate commission
            $commissionAmount = $doctor->calculateCommission($orderAmount);

            if ($commissionAmount > 0) {
                // Check if commission already exists for this bill and doctor
                $existingCommission = \App\Models\DoctorCommission::where('bill_id', $this->id)
                    ->where('doctor_id', $doctorId)
                    ->first();

                if (!$existingCommission) {
                    // Create commission record
                    $commission = \App\Models\DoctorCommission::create([
                        'lab_id' => $this->lab_id,
                        'doctor_id' => $doctorId,
                        'bill_id' => $this->id,
                        'test_order_id' => $orders->first()->id, // Store first order ID for reference
                        'order_amount' => $orderAmount,
                        'commission_amount' => $commissionAmount,
                        'status' => 'pending',
                        'commission_date' => now(),
                    ]);

                    // Update doctor's commission totals
                    $doctor->increment('total_commission_earned', $commissionAmount);
                    $doctor->increment('pending_commission', $commissionAmount);
                }
            }
        }
    }

    /**
     * Scope a query to only include bills for the current lab.
     */
    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    /**
     * Ensure bill has a payment token (for shareable payment link).
     */
    public function ensurePaymentToken(): string
    {
        if (empty($this->payment_token)) {
            $this->payment_token = \Illuminate\Support\Str::random(48);
            $this->save();
        }
        return $this->payment_token;
    }

    /**
     * Get the full URL for the public payment link.
     */
    public function getPaymentLinkUrl(): string
    {
        $this->ensurePaymentToken();
        return url('/pay-bill/' . $this->payment_token);
    }
}
