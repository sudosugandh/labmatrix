<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class InventoryItemController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request): View
    {
        $query = InventoryItem::forCurrentLab();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            match($request->stock_status) {
                'low' => $query->lowStock(),
                'out' => $query->outOfStock(),
                'expiring' => $query->expiringSoon(),
                'expired' => $query->expired(),
                default => null,
            };
        }

        $items = $query->latest()->paginate(15)->withQueryString();

        $labId = currentLabId();
        $stats = [
            'total' => InventoryItem::where('lab_id', $labId)->count(),
            'low_stock' => InventoryItem::where('lab_id', $labId)->lowStock()->count(),
            'out_of_stock' => InventoryItem::where('lab_id', $labId)->outOfStock()->count(),
            'total_value' => InventoryItem::where('lab_id', $labId)->sum(DB::raw('current_stock * cost')),
        ];

        return view('inventory.items.index', compact('items', 'stats'));
    }

    public function create(): View
    {
        return view('inventory.items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:inventory_items,sku,NULL,id,lab_id,' . currentLabId(),
            'unit' => 'required|string|max:50',
            'current_stock' => 'nullable|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $validated['lab_id'] = currentLabId();
        $validated['current_stock'] = $validated['current_stock'] ?? 0;

        InventoryItem::create($validated);

        return redirect()->route('inventory.items.index')
            ->with('success', 'Inventory item created successfully.');
    }

    public function show(InventoryItem $item): View
    {
        if ($item->lab_id !== currentLabId()) {
            abort(404);
        }

        $item->load(['adjustments.createdBy']);
        
        return view('inventory.items.show', compact('item'));
    }

    public function edit(InventoryItem $item): View
    {
        if ($item->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('inventory.items.edit', compact('item'));
    }

    public function update(Request $request, InventoryItem $item): RedirectResponse
    {
        if ($item->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:inventory_items,sku,' . $item->id . ',id,lab_id,' . currentLabId(),
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $item->update($validated);

        return redirect()->route('inventory.items.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(InventoryItem $item): RedirectResponse
    {
        if ($item->lab_id !== currentLabId()) {
            abort(404);
        }

        $item->delete();

        return redirect()->route('inventory.items.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    public function adjustStock(Request $request, InventoryItem $item): RedirectResponse
    {
        if ($item->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,remove,damage,correction',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            if ($validated['adjustment_type'] === 'add') {
                $this->inventoryService->addStock($item, $validated['quantity'], $validated['note'] ?? 'Stock added');
            } elseif ($validated['adjustment_type'] === 'correction') {
                $this->inventoryService->adjustStock($item, $validated['quantity'], $validated['note'] ?? 'Stock correction');
            } else {
                $this->inventoryService->removeStock($item, $validated['quantity'], $validated['note'] ?? 'Stock removed', $validated['adjustment_type']);
            }

            return redirect()->route('inventory.items.show', $item)
                ->with('success', 'Stock adjusted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function dashboard(): View
    {
        $labId = currentLabId();
        
        $stats = [
            'total_items' => InventoryItem::where('lab_id', $labId)->count(),
            'low_stock_count' => InventoryItem::where('lab_id', $labId)->lowStock()->count(),
            'out_of_stock_count' => InventoryItem::where('lab_id', $labId)->outOfStock()->count(),
            'expiring_soon_count' => InventoryItem::where('lab_id', $labId)->expiringSoon()->count(),
            'expired_count' => InventoryItem::where('lab_id', $labId)->expired()->count(),
            'total_value' => InventoryItem::where('lab_id', $labId)->sum(DB::raw('current_stock * cost')),
        ];

        $alerts = $this->inventoryService->getStockAlerts();
        
        $lowStockItems = InventoryItem::where('lab_id', $labId)->lowStock()->latest()->take(10)->get();
        $expiringItems = InventoryItem::where('lab_id', $labId)->expiringSoon()->orderBy('expiry_date')->take(10)->get();

        return view('inventory.dashboard', compact('stats', 'alerts', 'lowStockItems', 'expiringItems'));
    }
}
