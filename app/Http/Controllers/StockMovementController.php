<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\InventoryItem;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request): View
    {
        $query = StockMovement::forCurrentLab()->with(['inventoryItem', 'createdBy', 'purchaseOrder']);

        if ($request->filled('item_id')) {
            $query->where('inventory_item_id', $request->item_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }

        $movements = $query->latest('movement_date')->paginate(20)->withQueryString();

        return view('inventory.stock-movements.index', compact('movements'));
    }

    public function addStock(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->inventoryService->addStock(
                $item,
                $validated['quantity'],
                $validated['unit_cost'] ?? null,
                $validated['reference_number'] ?? null,
                $validated['reason'] ?? 'Stock added manually'
            );

            return redirect()->route('inventory.items.show', $item)
                ->with('success', 'Stock added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    public function removeStock(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->inventoryService->removeStock(
                $item,
                $validated['quantity'],
                $validated['reason'] ?? 'Stock used'
            );

            return redirect()->route('inventory.items.show', $item)
                ->with('success', 'Stock removed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to remove stock: ' . $e->getMessage());
        }
    }
}
