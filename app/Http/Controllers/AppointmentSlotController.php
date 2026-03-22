<?php

namespace App\Http\Controllers;

use App\Models\AppointmentSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentSlotController extends Controller
{
    public function index(): View
    {
        $slots = AppointmentSlot::forCurrentLab()
            ->orderBy('weekday')
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->paginate(30);

        $weekdays = AppointmentSlot::weekdayLabels();

        return view('appointment-slots.index', compact('slots', 'weekdays'));
    }

    public function create(): View
    {
        return view('appointment-slots.create', [
            'weekdays' => AppointmentSlot::weekdayLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weekday' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'max_bookings' => 'required|integer|min:1|max:100',
            'label' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['lab_id'] = currentLabId();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        AppointmentSlot::create($validated);

        return redirect()->route('appointment-slots.index')
            ->with('success', 'Time slot added.');
    }

    public function edit(AppointmentSlot $appointment_slot): View
    {
        $this->authorizeSlot($appointment_slot);

        return view('appointment-slots.edit', [
            'slot' => $appointment_slot,
            'weekdays' => AppointmentSlot::weekdayLabels(),
        ]);
    }

    public function update(Request $request, AppointmentSlot $appointment_slot): RedirectResponse
    {
        $this->authorizeSlot($appointment_slot);

        $validated = $request->validate([
            'weekday' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'max_bookings' => 'required|integer|min:1|max:100',
            'label' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $appointment_slot->update($validated);

        return redirect()->route('appointment-slots.index')
            ->with('success', 'Time slot updated.');
    }

    public function destroy(AppointmentSlot $appointment_slot): RedirectResponse
    {
        $this->authorizeSlot($appointment_slot);

        $appointment_slot->delete();

        return redirect()->route('appointment-slots.index')
            ->with('success', 'Time slot removed.');
    }

    protected function authorizeSlot(AppointmentSlot $slot): void
    {
        if ($slot->lab_id !== currentLabId()) {
            abort(404);
        }
    }
}
