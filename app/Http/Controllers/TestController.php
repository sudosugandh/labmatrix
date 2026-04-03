<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestParameter;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $labId = currentLabId();
        
        $query = Test::where('lab_id', $labId);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        $tests = $query->withCount('parameters')->latest()->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Test::where('lab_id', $labId)->count(),
            'avg_price' => Test::where('lab_id', $labId)->avg('price'),
            'min_price' => Test::where('lab_id', $labId)->min('price'),
            'max_price' => Test::where('lab_id', $labId)->max('price'),
        ];
        
        return view('tests.index', compact('tests', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['lab_id'] = currentLabId();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('tests', 'public');
        }

        Test::create($validated);

        return redirect()->route('tests.index')
            ->with('success', 'Test created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        // Ensure test belongs to current lab
        if ($test->lab_id !== currentLabId()) {
            abort(404);
        }

        $test->load('parameters');
        return view('tests.edit', compact('test'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test): RedirectResponse
    {
        // Ensure test belongs to current lab
        if ($test->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'report_template_id' => 'nullable|exists:diagnostic_report_templates,id',
        ]);

        // Verify template belongs to current lab if provided
        if (!empty($validated['report_template_id'])) {
            $template = \App\Models\DiagnosticReportTemplate::where('id', $validated['report_template_id'])
                ->where('lab_id', currentLabId())
                ->first();
            
            if (!$template) {
                return back()->withErrors(['report_template_id' => 'Selected template does not belong to your lab.'])->withInput();
            }
        }

        if ($request->hasFile('image')) {
            if ($test->image) {
                Storage::disk('public')->delete($test->image);
            }
            $validated['image'] = $request->file('image')->store('tests', 'public');
        }

        $test->update($validated);

        return redirect()->route('tests.index')
            ->with('success', 'Test updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test): RedirectResponse
    {
        // Ensure test belongs to current lab
        if ($test->lab_id !== currentLabId()) {
            abort(404);
        }

        $test->delete();

        return redirect()->route('tests.index')
            ->with('success', 'Test deleted successfully.');
    }

    /**
     * Store a new parameter for a test.
     */
    public function storeParameter(Request $request, Test $test): RedirectResponse
    {
        // Ensure test belongs to current lab
        if ($test->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'parameter_name' => 'required|string|max:255',
            'method_name' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'reference_range_low' => 'nullable|numeric',
            'reference_range_high' => 'nullable|numeric',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['lab_id'] = currentLabId();
        $validated['test_id'] = $test->id;
        $validated['sort_order'] = $validated['sort_order'] ?? ($test->parameters()->max('sort_order') ?? 0) + 1;

        TestParameter::create($validated);

        return redirect()->back()
            ->with('success', 'Parameter added successfully.');
    }

    /**
     * Update a parameter.
     */
    public function updateParameter(Request $request, Test $test, TestParameter $parameter): RedirectResponse
    {
        // Ensure test and parameter belong to current lab
        if ($test->lab_id !== currentLabId() || $parameter->lab_id !== currentLabId() || $parameter->test_id !== $test->id) {
            abort(404);
        }

        $validated = $request->validate([
            'parameter_name' => 'required|string|max:255',
            'method_name' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'reference_range_low' => 'nullable|numeric',
            'reference_range_high' => 'nullable|numeric',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $parameter->update($validated);

        return redirect()->back()
            ->with('success', 'Parameter updated successfully.');
    }

    /**
     * Delete a parameter.
     */
    public function destroyParameter(Test $test, TestParameter $parameter): RedirectResponse
    {
        // Ensure test and parameter belong to current lab
        if ($test->lab_id !== currentLabId() || $parameter->lab_id !== currentLabId() || $parameter->test_id !== $test->id) {
            abort(404);
        }

        $parameter->delete();

        return redirect()->back()
            ->with('success', 'Parameter deleted successfully.');
    }
}
