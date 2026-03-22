<?php

namespace App\Http\Controllers;

use App\Models\InventoryAdjustment;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryAdjustmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = InventoryAdjustment::forCurrentLab()
            ->with(['item', 'createdBy'])
            ->latest();

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('adjustment_type')) {
            $query->where('adjustment_type', $request->adjustment_type);
        }

        $adjustments = $query->paginate(20)->withQueryString();

        return view('inventory.adjustments.index', compact('adjustments'));
    }
}
