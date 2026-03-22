<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\TestOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $labId = currentLabId();
        
        $query = Bill::where('lab_id', $labId)
            ->with(['patient', 'createdBy']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($patientQuery) use ($search) {
                      $patientQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('bill_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('bill_date', '<=', $request->date_to);
        }
        
        $bills = $query->latest('bill_date')->paginate(15)->withQueryString();

        $stats = [
            'total_bills' => Bill::where('lab_id', $labId)->count(),
            'pending_amount' => Bill::where('lab_id', $labId)
                ->where('status', '!=', 'paid')
                ->sum('due_amount'),
            'paid_amount' => Bill::where('lab_id', $labId)
                ->where('status', 'paid')
                ->sum('total'),
            'today_revenue' => Payment::where('lab_id', $labId)
                ->whereDate('payment_date', today())
                ->sum('amount'),
            'this_month' => Payment::where('lab_id', $labId)
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
        ];

        return view('bills.index', compact('bills', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $patients = Patient::where('lab_id', currentLabId())->get();
        $pendingOrders = TestOrder::where('lab_id', currentLabId())
            ->whereNull('bill_id')
            ->where('payment_status', 'pending')
            ->with(['patient', 'test', 'package'])
            ->get();

        return view('bills.create', compact('patients', 'pendingOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bill_date' => 'required|date',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:test_orders,id',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Verify patient belongs to current lab
        $patient = Patient::where('id', $validated['patient_id'])
            ->where('lab_id', currentLabId())
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Get test orders and calculate totals
            $orders = TestOrder::whereIn('id', $validated['order_ids'])
                ->where('lab_id', currentLabId())
                ->whereNull('bill_id')
                ->with(['test', 'package'])
                ->get();

            if ($orders->isEmpty()) {
                return back()->withErrors(['order_ids' => 'No valid test orders selected.'])->withInput();
            }

            $subtotal = $orders->sum(function ($order) {
                return $order->final_price > 0 ? $order->final_price : $order->test->price;
            });

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;

            // Create bill
            $bill = Bill::create([
                'lab_id' => currentLabId(),
                'patient_id' => $validated['patient_id'],
                'bill_number' => Bill::generateBillNumber(),
                'bill_date' => $validated['bill_date'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => 0,
                'due_amount' => $total,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Update test orders with bill_id and pricing
            foreach ($orders as $order) {
                $price = $order->final_price > 0 ? $order->final_price : $order->test->price;
                $order->update([
                    'bill_id' => $bill->id,
                    'price' => $price,
                    'final_price' => $price,
                ]);
            }

            DB::commit();

            return redirect()->route('bills.show', $bill)
                ->with('success', 'Bill created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create bill: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill): View
    {
        if ($bill->lab_id !== currentLabId()) {
            abort(404);
        }

        $bill->load(['patient', 'testOrders.test', 'testOrders.package', 'payments.receivedBy', 'createdBy']);

        return view('bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill): View
    {
        if ($bill->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('bills.edit', compact('bill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill): RedirectResponse
    {
        if ($bill->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $subtotal = $bill->subtotal;
        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;
        $total = $subtotal - $discount + $tax;

        $bill->update([
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'due_amount' => max(0, $total - $bill->paid_amount),
            'notes' => $validated['notes'] ?? $bill->notes,
        ]);

        $bill->updatePaymentStatus();

        return redirect()->route('bills.show', $bill)
            ->with('success', 'Bill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill): RedirectResponse
    {
        if ($bill->lab_id !== currentLabId()) {
            abort(404);
        }

        if ($bill->payments()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete bill with payments.']);
        }

        // Remove bill_id from test orders
        $bill->testOrders()->update(['bill_id' => null]);

        $bill->delete();

        return redirect()->route('bills.index')
            ->with('success', 'Bill deleted successfully.');
    }

    /**
     * Print/Download invoice
     */
    public function print(Bill $bill): View
    {
        if ($bill->lab_id !== currentLabId()) {
            abort(404);
        }

        $bill->load(['patient', 'testOrders.test', 'testOrders.package', 'payments', 'createdBy']);
        $lab = currentLab();

        return view('bills.print', compact('bill', 'lab'));
    }
}
