<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Store a newly created payment.
     */
    public function store(Request $request, Bill $bill): RedirectResponse
    {
        if ($bill->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $bill->due_amount,
            'payment_method' => 'required|in:cash,card,online,cheque,bank_transfer',
            'transaction_id' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255|required_if:payment_method,cheque',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'lab_id' => currentLabId(),
                'bill_id' => $bill->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'cheque_number' => $validated['cheque_number'] ?? null,
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'] ?? null,
                'received_by' => Auth::id(),
            ]);

            $bill->updatePaymentStatus();

            DB::commit();

            return redirect()->route('bills.show', $bill)
                ->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record payment: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        if ($payment->lab_id !== currentLabId()) {
            abort(404);
        }

        $bill = $payment->bill;

        DB::beginTransaction();
        try {
            $payment->delete();
            $bill->updatePaymentStatus();

            DB::commit();

            return redirect()->route('bills.show', $bill)
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete payment: ' . $e->getMessage()]);
        }
    }
}
