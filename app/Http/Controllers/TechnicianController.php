<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Only allow access for large level labs
        if (!isLargeLab()) {
            abort(403, 'Technician features are only available for large level diagnostic centers.');
        }

        $technicians = Technician::forCurrentLab()->latest()->paginate(10);
        return view('technicians.index', compact('technicians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Only allow access for large level labs
        if (!isLargeLab()) {
            abort(403, 'Technician features are only available for large level diagnostic centers.');
        }

        return view('technicians.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Only allow access for large level labs
        if (!isLargeLab()) {
            abort(403, 'Technician features are only available for large level diagnostic centers.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        Technician::create(array_merge($validated, ['lab_id' => currentLabId()]));

        return redirect()->route('technicians.index')->with('success', 'Technician created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician): View
    {
        // Only allow access for large level labs
        if (!isLargeLab()) {
            abort(403, 'Technician features are only available for large level diagnostic centers.');
        }

        if ($technician->lab_id !== currentLabId()) {
            abort(404);
        }

        $technician->load('testOrders.test', 'testOrders.patient');
        return view('technicians.show', compact('technician'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technician $technician): View
    {
        // Only allow access for large level labs
        if (!isLargeLab()) {
            abort(403, 'Technician features are only available for large level diagnostic centers.');
        }

        if ($technician->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('technicians.edit', compact('technician'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technician $technician): RedirectResponse
    {
        // Only allow access for large level labs
        if (!isLargeLab()) {
            abort(403, 'Technician features are only available for large level diagnostic centers.');
        }

        if ($technician->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $technician->update($validated);

        return redirect()->route('technicians.index')->with('success', 'Technician updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician): RedirectResponse
    {
        // Only allow access for large level labs
        if (!isLargeLab()) {
            abort(403, 'Technician features are only available for large level diagnostic centers.');
        }

        if ($technician->lab_id !== currentLabId()) {
            abort(404);
        }

        $technician->delete();

        return redirect()->route('technicians.index')->with('success', 'Technician deleted successfully.');
    }
}
