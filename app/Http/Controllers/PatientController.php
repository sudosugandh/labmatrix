<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $labId = currentLabId();
        
        $query = Patient::where('lab_id', $labId);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        // Filter by age range
        if ($request->filled('age_min')) {
            $query->where('age', '>=', $request->age_min);
        }
        if ($request->filled('age_max')) {
            $query->where('age', '<=', $request->age_max);
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $patients = $query->paginate(15)->withQueryString();
        
        // Statistics - Use caching for better performance
        $cacheKey = "patient_stats_{$labId}";
        $stats = cache()->remember($cacheKey, 300, function() use ($labId) {
            return [
                'total' => Patient::where('lab_id', $labId)->count(),
                'male' => Patient::where('lab_id', $labId)->where('gender', 'male')->count(),
                'female' => Patient::where('lab_id', $labId)->where('gender', 'female')->count(),
                'today' => Patient::where('lab_id', $labId)->whereDate('created_at', today())->count(),
            ];
        });
        
        return view('patients.index', compact('patients', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string',
        ]);

        $validated['lab_id'] = currentLabId();

        Patient::create($validated);
        
        // Clear cache
        cache()->forget("patient_stats_{$validated['lab_id']}");

        return redirect()->route('patients.index')
            ->with('success', 'Patient created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        // Ensure patient belongs to current lab
        if ($patient->lab_id !== currentLabId()) {
            abort(404);
        }

        $patient->load(['testOrders.test', 'reports']);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        // Ensure patient belongs to current lab
        if ($patient->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient): RedirectResponse
    {
        // Ensure patient belongs to current lab
        if ($patient->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string',
        ]);

        $patient->update($validated);
        
        // Clear cache
        cache()->forget("patient_stats_{$patient->lab_id}");

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        // Ensure patient belongs to current lab
        if ($patient->lab_id !== currentLabId()) {
            abort(404);
        }

        $labId = $patient->lab_id;
        $patient->delete();
        
        // Clear cache
        cache()->forget("patient_stats_{$labId}");

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
