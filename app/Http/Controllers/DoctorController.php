<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorCommission;
use App\Models\TestOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Doctor::forCurrentLab()->withCount('testOrders');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('clinic_name', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $doctors = $query->latest()->paginate(15)->withQueryString();

        // Calculate pending commissions for each doctor
        foreach ($doctors as $doctor) {
            $doctor->pending_commission = DoctorCommission::where('doctor_id', $doctor->id)
                ->where('status', 'pending')
                ->sum('commission_amount');
        }

        // Statistics
        $labId = currentLabId();
        $stats = [
            'total' => Doctor::where('lab_id', $labId)->count(),
            'active' => Doctor::where('lab_id', $labId)->where('is_active', true)->count(),
            'inactive' => Doctor::where('lab_id', $labId)->where('is_active', false)->count(),
            'total_referrals' => TestOrder::where('lab_id', $labId)->whereNotNull('doctor_id')->count(),
        ];

        return view('doctors.index', compact('doctors', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'clinic_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'registration_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_rate' => 'required|numeric|min:0',
            'portal_password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = array_merge($validated, [
            'lab_id' => currentLabId(),
            'is_active' => $request->has('is_active'),
        ]);
        unset($data['portal_password'], $data['portal_password_confirmation']);
        if ($request->filled('portal_password')) {
            $data['password'] = $request->portal_password;
        }

        Doctor::create($data);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor): View
    {
        if ($doctor->lab_id !== currentLabId()) {
            abort(404);
        }

        // Load related data
        $doctor->loadCount('testOrders');

        // Get recent referrals
        $recentOrders = TestOrder::where('doctor_id', $doctor->id)
            ->where('lab_id', currentLabId())
            ->with(['patient', 'test', 'package', 'bill'])
            ->latest()
            ->take(10)
            ->get();

        // Get commission statistics
        $commissionStats = [
            'total_earned' => $doctor->total_commission_earned,
            'total_paid' => $doctor->total_commission_paid,
            'pending' => $doctor->pending_commission,
        ];

        // Get recent commissions
        $recentCommissions = DoctorCommission::where('doctor_id', $doctor->id)
            ->where('lab_id', currentLabId())
            ->with(['testOrder', 'bill', 'paidBy'])
            ->latest()
            ->take(10)
            ->get();

        // Get monthly commission trend (last 6 months)
        $monthlyCommissions = DoctorCommission::where('doctor_id', $doctor->id)
            ->where('lab_id', currentLabId())
            ->where('status', 'paid')
            ->selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(commission_amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return view('doctors.show', compact('doctor', 'recentOrders', 'commissionStats', 'recentCommissions', 'monthlyCommissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor): View
    {
        if ($doctor->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        if ($doctor->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'clinic_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'registration_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_rate' => 'required|numeric|min:0',
            'portal_password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = array_merge($validated, [
            'is_active' => $request->has('is_active'),
        ]);
        unset($data['portal_password'], $data['portal_password_confirmation']);
        if ($request->filled('portal_password')) {
            $data['password'] = $request->portal_password;
        }

        $doctor->update($data);

        return redirect()->route('doctors.show', $doctor)
            ->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor): RedirectResponse
    {
        if ($doctor->lab_id !== currentLabId()) {
            abort(404);
        }

        // Check if doctor has any referrals
        if ($doctor->testOrders()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete doctor with existing referrals.']);
        }

        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }

    /**
     * Show commission report for a doctor.
     */
    public function commissions(Doctor $doctor, Request $request): View
    {
        if ($doctor->lab_id !== currentLabId()) {
            abort(404);
        }

        $query = DoctorCommission::where('doctor_id', $doctor->id)
            ->where('lab_id', currentLabId())
            ->with(['testOrder', 'bill', 'paidBy']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->where('commission_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('commission_date', '<=', $request->to_date);
        }

        $commissions = $query->latest()->paginate(20);

        return view('doctors.commissions', compact('doctor', 'commissions'));
    }

    /**
     * Mark commission as paid.
     */
    public function payCommission(Request $request, DoctorCommission $commission): RedirectResponse
    {
        if ($commission->lab_id !== currentLabId()) {
            abort(404);
        }

        if ($commission->status === 'paid') {
            return back()->withErrors(['error' => 'Commission already paid.']);
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $commission->update([
                'status' => 'paid',
                'payment_date' => $validated['payment_date'],
                'notes' => ($commission->notes ? $commission->notes."\n\n" : '').($validated['notes'] ?? ''),
                'paid_by' => auth()->id(),
            ]);

            // Update doctor's commission totals
            $doctor = $commission->doctor;
            $doctor->increment('total_commission_paid', $commission->commission_amount);
            $doctor->decrement('pending_commission', $commission->commission_amount);

            DB::commit();

            return back()->with('success', 'Commission marked as paid successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to mark commission as paid: '.$e->getMessage()]);
        }
    }
}
