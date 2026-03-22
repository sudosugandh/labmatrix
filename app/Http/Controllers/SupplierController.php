<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Supplier::forCurrentLab()->withCount(['purchaseOrders', 'inventoryItems']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $suppliers = $query->latest()->paginate(15)->withQueryString();

        $labId = currentLabId();
        $stats = [
            'total' => Supplier::where('lab_id', $labId)->count(),
            'active' => Supplier::where('lab_id', $labId)->where('is_active', true)->count(),
            'inactive' => Supplier::where('lab_id', $labId)->where('is_active', false)->count(),
        ];

        return view('inventory.suppliers.index', compact('suppliers', 'stats'));
    }

    public function create(): View
    {
        return view('inventory.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['lab_id'] = currentLabId();
        $validated['is_active'] = $validated['is_active'] ?? true;

        Supplier::create($validated);

        return redirect()->route('inventory.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier): View
    {
        if ($supplier->lab_id !== currentLabId()) {
            abort(404);
        }

        $supplier->load(['purchaseOrders' => function($q) {
            $q->latest()->take(10);
        }])->loadCount(['purchaseOrders', 'inventoryItems']);

        return view('inventory.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        if ($supplier->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('inventory.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        if ($supplier->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $supplier->update($validated);

        return redirect()->route('inventory.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->lab_id !== currentLabId()) {
            abort(404);
        }

        if ($supplier->purchaseOrders()->count() > 0) {
            return redirect()->route('inventory.suppliers.index')
                ->with('error', 'Cannot delete supplier with existing purchase orders.');
        }

        $supplier->delete();

        return redirect()->route('inventory.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
