<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\TestPackage;
use App\Models\TestOrder;
use App\Models\WebsiteSetting;
use App\Models\Menu;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublicAppointmentController extends Controller
{
    /**
     * Show the appointment booking form selection (doctor or patient).
     */
    public function create(Request $request)
    {
        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        // If booking_type is set, show the booking form
        $bookingType = $request->get('type'); // 'patient' or 'doctor'

        if ($bookingType) {
            // Get active packages for selection
            $packages = TestPackage::forCurrentLab()
                ->active()
                ->withCount('tests')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            // Get active doctors if booking as doctor
            $doctors = [];
            if ($bookingType === 'doctor') {
                $doctors = Doctor::forCurrentLab()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();
            }

            $labUsesAppointmentSlots = AppointmentSlot::forCurrentLab()->active()->exists();

            return view('website.appointments.create', compact('settings', 'menuItems', 'packages', 'bookingType', 'doctors', 'labUsesAppointmentSlots'));
        }

        // Show selection page
        return view('website.appointments.select-type', compact('settings', 'menuItems'));
    }

    /**
     * Store a new appointment booking.
     */
    public function store(Request $request)
    {
        $labId = currentLabId();
        $hasActiveSlots = AppointmentSlot::where('lab_id', $labId)->where('is_active', true)->exists();

        $rules = [
            'booking_type' => 'required|in:patient,doctor',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'age' => 'nullable|integer|min:1|max:150',
            'gender' => 'nullable|in:male,female,other',
            'appointment_date' => 'required|date|after_or_equal:today',
            'package_id' => 'nullable|exists:test_packages,id',
            'doctor_selection' => 'required_if:booking_type,doctor|in:registered,not_registered',
            'doctor_id' => [
                'required_if:doctor_selection,registered',
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('doctor_selection') === 'registered' && empty($value)) {
                        $fail('Please select your name from the list.');
                    }
                    if (!empty($value) && !\App\Models\Doctor::where('id', $value)->exists()) {
                        $fail('Selected doctor does not exist.');
                    }
                },
            ],
            'referral_number' => 'nullable|string|max:255',
            'doctor_name' => 'required_if:doctor_selection,not_registered|string|max:255',
            'doctor_phone' => 'required_if:doctor_selection,not_registered|string|max:20',
            'doctor_email' => 'nullable|email|max:255',
            'doctor_specialization' => 'nullable|string|max:255',
            'doctor_clinic' => 'nullable|string|max:255',
            'referral_number_manual' => 'nullable|string|max:255',
            'referring_doctor_name' => 'nullable|string|max:255',
            'referring_doctor_phone' => 'nullable|string|max:20',
            'prescription_file' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->hasFile($attribute)) {
                        $file = $request->file($attribute);
                        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                        
                        if (!in_array($file->getMimeType(), $allowedMimes) && !in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
                            $fail('The prescription file must be a file of type: pdf, jpg, jpeg, png.');
                        }
                        if ($file->getSize() > 5120 * 1024) { // 5MB in bytes
                            $fail('The prescription file must not be larger than 5MB.');
                        }
                    }
                },
            ],
            'notes' => 'nullable|string|max:1000',
        ];

        if ($hasActiveSlots) {
            $rules['appointment_slot_id'] = [
                'required',
                Rule::exists('appointment_slots', 'id')->where(fn ($q) => $q->where('lab_id', $labId)->where('is_active', true)),
            ];
        } else {
            $rules['appointment_time'] = 'required';
        }

        $validated = $request->validate($rules, [
            'doctor_selection.required_if' => 'Please select whether you are registered or not.',
            'doctor_id.required_if' => 'Please select your name from the list.',
            'doctor_name.required_if' => 'Your name is required.',
            'doctor_phone.required_if' => 'Your phone number is required.',
        ]);

        $appointmentSlotId = null;
        $appointmentTime = null;

        if ($hasActiveSlots) {
            $slot = AppointmentSlot::where('id', $validated['appointment_slot_id'])
                ->where('lab_id', $labId)
                ->where('is_active', true)
                ->first();

            if (!$slot) {
                return back()->withInput()->withErrors(['appointment_slot_id' => 'Invalid or inactive time slot.']);
            }

            $apptDate = Carbon::parse($validated['appointment_date'])->startOfDay();
            if ((int) $slot->weekday !== (int) $apptDate->dayOfWeek) {
                return back()->withInput()->withErrors(['appointment_slot_id' => 'That time does not match the selected day of the week.']);
            }

            $booked = Appointment::where('lab_id', $labId)
                ->whereDate('appointment_date', $apptDate)
                ->where('appointment_slot_id', $slot->id)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count();

            if ($booked >= $slot->max_bookings) {
                return back()->withInput()->withErrors(['appointment_slot_id' => 'This slot is full for that day. Please choose another time.']);
            }

            $appointmentTime = $slot->time_string;
            $appointmentSlotId = $slot->id;
        } else {
            $appointmentTime = $validated['appointment_time'];

            $existingAppointment = Appointment::where('lab_id', $labId)
                ->where('appointment_date', $validated['appointment_date'])
                ->where('appointment_time', $appointmentTime)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->first();

            if ($existingAppointment) {
                return back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'This time slot is already booked. Please choose another time.']);
            }
        }

        DB::beginTransaction();
        try {
            // Find or create patient
            $patient = Patient::where('lab_id', $labId)
                ->where('phone', $validated['phone'])
                ->first();

            if (!$patient) {
                $patient = Patient::create([
                    'lab_id' => $labId,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'] ?? null,
                    'age' => $validated['age'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                ]);
            } else {
                // Update patient info if provided
                $patient->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? $patient->email,
                    'age' => $validated['age'] ?? $patient->age,
                    'gender' => $validated['gender'] ?? $patient->gender,
                ]);
            }

            // Verify package belongs to current lab if provided
            $package = null;
            if (!empty($validated['package_id'])) {
                $package = TestPackage::where('id', $validated['package_id'])
                    ->where('lab_id', $labId)
                    ->where('is_active', true)
                    ->with('tests')
                    ->first();
                
                if (!$package) {
                    return back()
                        ->withInput()
                        ->withErrors(['package_id' => 'Selected package is invalid or not available.']);
                }
            }

            // Handle doctor registration if not registered
            $doctorId = null;
            $referralNumber = null;
            
            if ($validated['booking_type'] === 'doctor') {
                if ($validated['doctor_selection'] === 'registered') {
                    $doctorId = $validated['doctor_id'];
                    $referralNumber = $validated['referral_number'] ?? null;
                } else {
                    // Auto-create doctor record (inactive, needs admin approval)
                    $newDoctor = Doctor::create([
                        'lab_id' => $labId,
                        'name' => $validated['doctor_name'],
                        'phone' => $validated['doctor_phone'],
                        'email' => $validated['doctor_email'] ?? null,
                        'specialization' => $validated['doctor_specialization'] ?? null,
                        'clinic_name' => $validated['doctor_clinic'] ?? null,
                        'is_active' => false, // Inactive until admin approves
                        'commission_type' => 'percentage', // Default, admin can change
                        'commission_rate' => 0, // Default, admin can set
                    ]);
                    $doctorId = $newDoctor->id;
                    $referralNumber = $validated['referral_number_manual'] ?? null;
                }
            }

            // Handle prescription file upload
            $prescriptionFilePath = null;
            if ($request->hasFile('prescription_file')) {
                $file = $request->file('prescription_file');
                $fileName = 'prescriptions/' . $labId . '/' . time() . '_' . $file->getClientOriginalName();
                $prescriptionFilePath = $file->storeAs('public', $fileName);
                // Remove 'public/' prefix for database storage
                $prescriptionFilePath = str_replace('public/', '', $prescriptionFilePath);
            }

            // Create appointment
            $appointment = Appointment::create([
                'lab_id' => $labId,
                'patient_id' => $patient->id,
                'doctor_id' => $doctorId,
                'referring_doctor_name' => $validated['referring_doctor_name'] ?? null,
                'referring_doctor_phone' => $validated['referring_doctor_phone'] ?? null,
                'prescription_file' => $prescriptionFilePath,
                'package_id' => $package ? $package->id : null,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $appointmentTime,
                'appointment_slot_id' => $appointmentSlotId,
                'status' => 'scheduled',
                'booking_source' => $validated['booking_type'] === 'doctor' ? 'doctor' : 'public',
                'notes' => $validated['notes'] ?? null,
                'created_by' => null, // Public booking, no user
            ]);

            // Create notification for all admin users
            try {
                $notificationService = app(NotificationService::class);
                $packageInfo = $package ? " (Package: {$package->name})" : "";
                $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y');
                $notificationService->createForAllUsers(
                    'appointment',
                    'New Appointment Booked',
                    "New appointment booked by {$patient->name} on {$appointmentDate} at {$appointment->appointment_time}{$packageInfo}",
                    route('appointments.show', $appointment),
                    'calendar',
                    'blue',
                    ['appointment_id' => $appointment->id, 'patient_id' => $patient->id]
                );
            } catch (\Exception $e) {
                \Log::error('Failed to create notification for appointment: ' . $e->getMessage());
                // Don't fail the appointment booking if notification fails
            }

            // If doctor booking, also create test order with doctor reference
            if ($validated['booking_type'] === 'doctor' && $package) {
                // Update test orders to include doctor_id and referral_number
                TestOrder::where('lab_id', $labId)
                    ->where('patient_id', $patient->id)
                    ->where('package_id', $package->id)
                    ->whereNull('doctor_id')
                    ->update([
                        'doctor_id' => $validated['doctor_id'],
                        'referral_number' => $validated['referral_number'] ?? null,
                    ]);
            }

            // Auto-create test orders if package is selected
            if ($package) {
                $orders = [];
                foreach ($package->tests as $test) {
                    $orders[] = [
                        'lab_id' => $labId,
                        'patient_id' => $patient->id,
                        'test_id' => $test->id,
                        'package_id' => $package->id,
                        'doctor_id' => $doctorId,
                        'referral_number' => $referralNumber,
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

            // Send WhatsApp notification
            try {
                $whatsappService = app(\App\Services\WhatsAppService::class);
                $whatsappService->sendAppointmentBooked($appointment);
            } catch (\Exception $e) {
                \Log::error('WhatsApp notification failed: ' . $e->getMessage());
            }

            return redirect()
                ->route('public.appointments.confirmation', $appointment)
                ->with('success', 'Appointment booked successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Appointment booking error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to book appointment: ' . $e->getMessage()]);
        }
    }

    /**
     * Show appointment confirmation page.
     */
    public function confirmation(Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        $appointment->load(['patient', 'package', 'doctor']);

        return view('website.appointments.confirmation', compact('appointment', 'settings', 'menuItems'));
    }

    /**
     * Show appointment lookup/search page.
     */
    public function search()
    {
        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        return view('website.appointments.search', compact('settings', 'menuItems'));
    }

    /**
     * Show appointments for a patient by phone number.
     */
    public function results(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
        ]);

        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        // Find patient by phone number for current lab
        $patient = Patient::where('lab_id', currentLabId())
            ->where('phone', $validated['phone'])
            ->first();

        if (!$patient) {
            return view('website.appointments.results', compact('settings', 'menuItems'))
                ->with('error', 'No patient found with this phone number.');
        }

        // Get appointments for this patient
        $appointments = Appointment::where('lab_id', currentLabId())
            ->where('patient_id', $patient->id)
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        return view('website.appointments.results', compact('settings', 'menuItems', 'patient', 'appointments'));
    }

    /**
     * Show appointment details for patient.
     */
    public function show(Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        $appointment->load(['patient', 'package', 'doctor']);

        return view('website.appointments.show', compact('appointment', 'settings', 'menuItems'));
    }

    /**
     * Cancel appointment (patient self-service).
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        // Only allow cancellation of scheduled or confirmed appointments
        if (!in_array($appointment->status, ['scheduled', 'confirmed'])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        // Only allow patients to cancel their own public bookings
        if ($appointment->booking_source !== 'public') {
            return back()->with('error', 'You can only cancel appointments booked through the public portal.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . 'Cancelled by patient on ' . now()->format('Y-m-d H:i:s'),
        ]);

        // Send WhatsApp notification
        try {
            $whatsappService = app(\App\Services\WhatsAppService::class);
            $whatsappService->sendAppointmentCancelled($appointment);
        } catch (\Exception $e) {
            \Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        $menuItems = $menu ? $menu->items : collect();

        return redirect()
            ->route('public.appointments.show', $appointment)
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Print/download appointment confirmation.
     */
    public function print(Appointment $appointment)
    {
        // Ensure appointment belongs to current lab
        if ($appointment->lab_id !== currentLabId()) {
            abort(404);
        }

        $appointment->load('patient');
        $settings = WebsiteSetting::getForCurrentLab();

        return view('website.appointments.print', compact('appointment', 'settings'));
    }

    /**
     * JSON: available slot options for a date (public booking).
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
        ]);

        $labId = currentLabId();
        $date = Carbon::parse($request->date)->startOfDay();
        $weekday = (int) $date->dayOfWeek;

        $slots = AppointmentSlot::where('lab_id', $labId)
            ->where('is_active', true)
            ->where('weekday', $weekday)
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get();

        $out = [];
        foreach ($slots as $slot) {
            $booked = Appointment::where('lab_id', $labId)
                ->whereDate('appointment_date', $date)
                ->where('appointment_slot_id', $slot->id)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count();
            $remaining = max(0, $slot->max_bookings - $booked);
            $out[] = [
                'id' => $slot->id,
                'label' => $slot->label ?: $slot->display_time,
                'time' => $slot->time_string,
                'display_time' => $slot->display_time,
                'remaining' => $remaining,
                'full' => $remaining === 0,
            ];
        }

        return response()->json(['slots' => $out]);
    }
}
