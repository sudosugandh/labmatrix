<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\InventoryItem;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request): View
    {
        $query = PurchaseOrder::forCurrentLab()->with(['supplier', 'createdBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $labId = currentLabId();
        $stats = [
            'total' => PurchaseOrder::where('lab_id', $labId)->count(),
            'pending' => PurchaseOrder::where('lab_id', $labId)->where('status', 'pending')->count(),
            'ordered' => PurchaseOrder::where('lab_id', $labId)->where('status', 'ordered')->count(),
            'received' => PurchaseOrder::where('lab_id', $labId)->where('status', 'received')->count(),
            'total_value' => PurchaseOrder::where('lab_id', $labId)->sum('total_amount'),
        ];

        $suppliers = Supplier::forCurrentLab()->active()->orderBy('name')->get();

        return view('inventory.purchase-orders.index', compact('orders', 'stats', 'suppliers'));
    }

    public function create(): View
    {
        $suppliers = Supplier::forCurrentLab()->active()->orderBy('name')->get();
        $items = InventoryItem::forCurrentLab()->active()->orderBy('name')->get();
        return view('inventory.purchase-orders.create', compact('suppliers', 'items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity_ordered' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::create([
                'lab_id' => currentLabId(),
                'po_number' => PurchaseOrder::generatePONumber(),
                'supplier_id' => $validated['supplier_id'],
                'created_by' => auth()->id(),
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'status' => 'draft',
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
            ]);

            $subtotal = 0;
            foreach ($validated['items'] as $itemData) {
                $item = new \App\Models\PurchaseOrderItem();
                $item->purchase_order_id = $po->id;
                $item->inventory_item_id = $itemData['inventory_item_id'];
                $item->quantity_ordered = $itemData['quantity_ordered'];
                $item->unit_cost = $itemData['unit_cost'];
                $item->discount = $itemData['discount'] ?? 0;
                $item->tax_rate = $itemData['tax_rate'] ?? 0;
                $item->calculateLineTotal();
                $item->save();
                $subtotal += $item->line_total;
            }

            $po->subtotal = $subtotal;
            $po->calculateTotal();
            $po->save();

            DB::commit();

            return redirect()->route('inventory.purchase-orders.show', $po)
                ->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create purchase order: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        if ($purchaseOrder->lab_id !== currentLabId()) {
            abort(404);
        }

        $purchaseOrder->load(['supplier', 'createdBy', 'items.inventoryItem', 'stockMovements']);
        
        return view('inventory.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        if ($purchaseOrder->lab_id !== currentLabId()) {
            abort(404);
        }

        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('inventory.purchase-orders.show', $purchaseOrder)
                ->with('error', 'Only draft purchase orders can be edited.');
        }

        $suppliers = Supplier::forCurrentLab()->active()->orderBy('name')->get();
        $items = InventoryItem::forCurrentLab()->active()->orderBy('name')->get();
        
        return view('inventory.purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'items'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->lab_id !== currentLabId() || $purchaseOrder->status !== 'draft') {
            abort(404);
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity_ordered' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete existing items
            $purchaseOrder->items()->delete();

            // Create new items
            $subtotal = 0;
            foreach ($validated['items'] as $itemData) {
                $item = new \App\Models\PurchaseOrderItem();
                $item->purchase_order_id = $purchaseOrder->id;
                $item->inventory_item_id = $itemData['inventory_item_id'];
                $item->quantity_ordered = $itemData['quantity_ordered'];
                $item->unit_cost = $itemData['unit_cost'];
                $item->discount = $itemData['discount'] ?? 0;
                $item->tax_rate = $itemData['tax_rate'] ?? 0;
                $item->calculateLineTotal();
                $item->save();
                $subtotal += $item->line_total;
            }

            $purchaseOrder->subtotal = $subtotal;
            $purchaseOrder->calculateTotal();
            $purchaseOrder->save();

            DB::commit();

            return redirect()->route('inventory.purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update purchase order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,pending,approved,ordered,partial,received,cancelled',
        ]);

        $purchaseOrder->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Purchase order status updated.');
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $itemData) {
                $poItem = $purchaseOrder->items()->findOrFail($itemData['id']);
                $quantityReceived = $itemData['quantity_received'];
                
                if ($quantityReceived > 0) {
                    $poItem->quantity_received += $quantityReceived;
                    $poItem->save();

                    // Add stock to inventory
                    $this->inventoryService->addStock(
                        $poItem->inventoryItem,
                        $quantityReceived,
                        $poItem->unit_cost,
                        $purchaseOrder->po_number,
                        'Received from PO: ' . $purchaseOrder->po_number,
                        $purchaseOrder->id
                    );
                }
            }

            // Update PO status
            $totalOrdered = $purchaseOrder->items->sum('quantity_ordered');
            $totalReceived = $purchaseOrder->items->sum('quantity_received');
            
            if ($totalReceived >= $totalOrdered) {
                $purchaseOrder->status = 'received';
                $purchaseOrder->received_date = now();
            } elseif ($totalReceived > 0) {
                $purchaseOrder->status = 'partial';
            }
            
            $purchaseOrder->save();

            DB::commit();

            return redirect()->route('inventory.purchase-orders.show', $purchaseOrder)
                ->with('success', 'Stock received and added to inventory.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to receive stock: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->lab_id !== currentLabId()) {
            abort(404);
        }

        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('inventory.purchase-orders.index')
                ->with('error', 'Only draft purchase orders can be deleted.');
        }

        $purchaseOrder->delete();

        return redirect()->route('inventory.purchase-orders.index')
            ->with('success', 'Purchase order deleted successfully.');
    }
}
