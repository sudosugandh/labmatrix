<?php

namespace App\Http\Controllers;

use App\Models\TestPackage;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TestPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = TestPackage::forCurrentLab()->withCount('tests')->with('tests');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $testPackages = $query->orderBy('sort_order')->paginate(10);

        return view('test-packages.index', compact('testPackages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tests = Test::forCurrentLab()->orderBy('name')->get();
        return view('test-packages.create', compact('tests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'test_ids' => 'required|array|min:1',
            'test_ids.*' => 'exists:tests,id',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Verify all tests belong to current lab
        $testCount = Test::forCurrentLab()
            ->whereIn('id', $validated['test_ids'])
            ->count();

        if ($testCount !== count($validated['test_ids'])) {
            return back()
                ->withInput()
                ->withErrors(['test_ids' => 'Some selected tests are invalid or do not belong to your lab.']);
        }

        DB::beginTransaction();
        try {
            $discount = $validated['discount'] ?? 0;
            $price = $validated['price'];
            $finalPrice = $discount > 0 ? $price - ($price * $discount / 100) : $price;

            $package = TestPackage::create([
                'lab_id' => currentLabId(),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $price,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'is_active' => $request->has('is_active'),
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            $package->tests()->attach($validated['test_ids']);

            DB::commit();

            return redirect()
                ->route('test-packages.index')
                ->with('success', 'Test package created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create test package. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TestPackage $testPackage): View
    {
        if ($testPackage->lab_id !== currentLabId()) {
            abort(404);
        }
        $testPackage->load('tests');
        return view('test-packages.show', compact('testPackage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TestPackage $testPackage): View
    {
        if ($testPackage->lab_id !== currentLabId()) {
            abort(404);
        }
        $tests = Test::forCurrentLab()->orderBy('name')->get();
        $testPackage->load('tests');
        $selectedTestIds = $testPackage->tests->pluck('id')->toArray();

        return view('test-packages.edit', compact('testPackage', 'tests', 'selectedTestIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestPackage $testPackage): RedirectResponse
    {
        if ($testPackage->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'test_ids' => 'required|array|min:1',
            'test_ids.*' => 'exists:tests,id',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Verify all tests belong to current lab
        $testCount = Test::forCurrentLab()
            ->whereIn('id', $validated['test_ids'])
            ->count();

        if ($testCount !== count($validated['test_ids'])) {
            return back()
                ->withInput()
                ->withErrors(['test_ids' => 'Some selected tests are invalid or do not belong to your lab.']);
        }

        DB::beginTransaction();
        try {
            $discount = $validated['discount'] ?? 0;
            $price = $validated['price'];
            $finalPrice = $discount > 0 ? $price - ($price * $discount / 100) : $price;

            $testPackage->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $price,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'is_active' => $request->has('is_active'),
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            $testPackage->tests()->sync($validated['test_ids']);

            DB::commit();

            return redirect()
                ->route('test-packages.index')
                ->with('success', 'Test package updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update test package. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestPackage $testPackage): RedirectResponse
    {
        if ($testPackage->lab_id !== currentLabId()) {
            abort(404);
        }

        $testPackage->delete();

        return redirect()
            ->route('test-packages.index')
            ->with('success', 'Test package deleted successfully.');
    }
}
