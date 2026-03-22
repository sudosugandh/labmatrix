<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\TestPackage;
use App\Models\TestOrder;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Appointment::forCurrentLab()
            ->with(['patient', 'createdBy', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by booking source
        if ($request->filled('booking_source')) {
            $query->where('booking_source', $request->booking_source);
        }

        $appointments = $query->paginate(15);
        $patients = Patient::forCurrentLab()->orderBy('name')->get();
        $statuses = ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'];
        
        // Statistics - Use caching for better performance
        $labId = currentLabId();
        $cacheKey = "appointment_stats_{$labId}_" . now()->format('Y-m-d');
        $stats = cache()->remember($cacheKey, 300, function() use ($labId) {
            return [
                'today' => Appointment::forCurrentLab()
                    ->whereDate('appointment_date', today())
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count(),
                'upcoming' => Appointment::forCurrentLab()
                    ->where('appointment_date', '>', today())
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count(),
                'completed' => Appointment::forCurrentLab()
                    ->where('status', 'completed')
                    ->whereMonth('appointment_date', now()->month)
                    ->count(),
                'cancelled' => Appointment::forCurrentLab()
                    ->where('status', 'cancelled')
                    ->whereMonth('appointment_date', now()->month)
                    ->count(),
            ];
        });

        return view('appointments.index', compact('appointments', 'patients', 'statuses', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::forCurrentLab()->orderBy('name')->get();
        $packages = TestPackage::forCurrentLab()
            ->active()
            ->withCount('tests')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        return view('appointments.create', compact('patients', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'package_id' => 'nullable|exists:test_packages,id',
            'status' => 'required|in:scheduled,confirmed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if patient belongs to current lab
        $patient = Patient::forCurrentLab()->findOrFail($validated['patient_id']);

        DB::beginTransaction();
        try {
            // Verify package belongs to current lab if provided
            $package = null;
            if (!empty($validated['package_id'])) {
                $package = TestPackage::where('id', $validated['package_id'])
                    ->where('lab_id', currentLabId())
                    ->where('is_active', true)
                    ->with('tests')
                    ->first();
                
                if (!$package) {
                    return back()
                        ->withInput()
                        ->withErrors(['package_id' => 'Selected package is invalid or not available.']);
                }
            }

            $appointment = Appointment::create([
                'lab_id' => currentLabId(),
                'patient_id' => $validated['patient_id'],
                'package_id' => $package ? $package->id : null,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'status' => $validated['status'],
                'booking_source' => 'admin',
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Create notification for new appointment
            NotificationService::notifyAll(
                'appointment',
                'New Appointment Scheduled',
                "New appointment scheduled for {$patient->name} on {$appointment->appointment_date->format('M d, Y')} at {$appointment->appointment_time}",
                $appointment,
                'blue',
                ['action_url' => route('appointments.show', $appointment)]
            );

            // Auto-create test orders if package is selected and status is confirmed
            if ($package && $validated['status'] === 'confirmed') {
                $orders = [];
                foreach ($package->tests as $test) {
                    $orders[] = [
                        'lab_id' => currentLabId(),
                        'patient_id' => $patient->id,
                        'test_id' => $test->id,
                        'package_id' => $package->id,
                        'status' => 'pending',
                        'sample_status' => 'pending',
                        'report_status' => 'pending',
                        'price' => $test->price,
                        'final_price' => $test->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                TestOrder::insert($orders);
            }

            DB::commit();

            return redirect()
                ->route('appointments.show', $appointment)
                ->with('success', 'Appointment created successfully.' . ($package && $validated['status'] === 'confirmed' ? ' Test orders created automatically.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create appointment: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $appointment->load(['patient', 'createdBy', 'package']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $patients = Patient::forCurrentLab()->orderBy('name')->get();
        $packages = TestPackage::forCurrentLab()
            ->active()
            ->withCount('tests')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $appointment->load('package');
        return view('appointments.edit', compact('appointment', 'patients', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'package_id' => 'nullable|exists:test_packages,id',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if patient belongs to current lab
        $patient = Patient::forCurrentLab()->findOrFail($validated['patient_id']);

        DB::beginTransaction();
        try {
            $oldStatus = $appointment->status;
            $oldPackageId = $appointment->package_id;

            // Verify package belongs to current lab if provided
            $package = null;
            if (!empty($validated['package_id'])) {
                $package = TestPackage::where('id', $validated['package_id'])
                    ->where('lab_id', currentLabId())
                    ->where('is_active', true)
                    ->with('tests')
                    ->first();
                
                if (!$package) {
                    return back()
                        ->withInput()
                        ->withErrors(['package_id' => 'Selected package is invalid or not available.']);
                }
            }

            $appointment->update([
                'patient_id' => $validated['patient_id'],
                'package_id' => $package ? $package->id : null,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Auto-create test orders when status changes to 'confirmed' and package exists
            if ($validated['status'] === 'confirmed' && $package && $oldStatus !== 'confirmed') {
                // Check if orders already exist for this appointment's package
                $existingOrders = TestOrder::where('lab_id', currentLabId())
                    ->where('patient_id', $patient->id)
                    ->where('package_id', $package->id)
                    ->whereNull('bill_id')
                    ->count();

                if ($existingOrders === 0) {
                    $orders = [];
                    foreach ($package->tests as $test) {
                        $orders[] = [
                            'lab_id' => currentLabId(),
                            'patient_id' => $patient->id,
                            'test_id' => $test->id,
                            'package_id' => $package->id,
                            'status' => 'pending',
                            'sample_status' => 'pending',
                            'report_status' => 'pending',
                            'price' => $test->price,
                            'final_price' => $test->price,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    TestOrder::insert($orders);
                }
            }

            DB::commit();

            return redirect()
                ->route('appointments.show', $appointment)
                ->with('success', 'Appointment updated successfully.' . ($validated['status'] === 'confirmed' && $package && $oldStatus !== 'confirmed' ? ' Test orders created automatically.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update appointment: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $appointment->status;
            $appointment->update(['status' => $validated['status']]);

            // Create notification for status change
            $statusMessages = [
                'confirmed' => 'Appointment confirmed',
                'completed' => 'Appointment completed',
                'cancelled' => 'Appointment cancelled',
                'no_show' => 'Patient did not show up',
            ];

            if (isset($statusMessages[$validated['status']])) {
                NotificationService::notifyAll(
                    'appointment',
                    $statusMessages[$validated['status']],
                    "Appointment for {$appointment->patient->name} has been {$validated['status']}",
                    $appointment,
                    $validated['status'] === 'cancelled' ? 'red' : ($validated['status'] === 'completed' ? 'green' : 'blue'),
                    [],
                    route('appointments.show', $appointment)
                );
            }

            // Send WhatsApp notification for cancellation
            if ($validated['status'] === 'cancelled' && $oldStatus !== 'cancelled') {
                try {
                    $whatsappService = app(\App\Services\WhatsAppService::class);
                    $whatsappService->sendAppointmentCancelled($appointment);
                } catch (\Exception $e) {
                    \Log::error('WhatsApp notification failed: ' . $e->getMessage());
                }
            }

            // Auto-create test orders when status changes to 'confirmed' and package exists
            if ($validated['status'] === 'confirmed' && $oldStatus !== 'confirmed' && $appointment->package_id) {
                $appointment->load(['package.tests', 'patient']);
                
                if ($appointment->package && $appointment->patient) {
                    // Check if orders already exist for this appointment's package
                    $existingOrders = TestOrder::where('lab_id', currentLabId())
                        ->where('patient_id', $appointment->patient_id)
                        ->where('package_id', $appointment->package_id)
                        ->whereNull('bill_id')
                        ->count();

                    if ($existingOrders === 0) {
                        $orders = [];
                        foreach ($appointment->package->tests as $test) {
                            $orders[] = [
                                'lab_id' => currentLabId(),
                                'patient_id' => $appointment->patient_id,
                                'test_id' => $test->id,
                                'package_id' => $appointment->package_id,
                                'status' => 'pending',
                                'sample_status' => 'pending',
                                'report_status' => 'pending',
                                'price' => $test->price,
                                'final_price' => $test->price,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        TestOrder::insert($orders);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('appointments.show', $appointment)
                ->with('success', 'Appointment status updated successfully.' . ($validated['status'] === 'confirmed' && $appointment->package_id && $oldStatus !== 'confirmed' ? ' Test orders created automatically.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('appointments.show', $appointment)
                ->withErrors(['error' => 'Failed to update status: ' . $e->getMessage()]);
        }
    }
}
