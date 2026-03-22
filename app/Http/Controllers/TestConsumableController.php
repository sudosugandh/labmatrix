<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestConsumable;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestConsumableController extends Controller
{
    public function index(Test $test): View
    {
        if ($test->lab_id !== currentLabId()) {
            abort(404);
        }

        $consumables = TestConsumable::where('lab_id', currentLabId())
            ->where('test_id', $test->id)
            ->with('item')
            ->get();

        $items = InventoryItem::forCurrentLab()->orderBy('name')->get();

        return view('tests.consumables.index', compact('test', 'consumables', 'items'));
    }

    public function store(Request $request, Test $test): RedirectResponse
    {
        if ($test->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'quantity_required' => 'required|numeric|min:0.01',
        ]);

        TestConsumable::updateOrCreate(
            [
                'lab_id' => currentLabId(),
                'test_id' => $test->id,
                'item_id' => $validated['item_id'],
            ],
            [
                'quantity_required' => $validated['quantity_required'],
            ]
        );

        return redirect()->route('tests.consumables.index', $test)
            ->with('success', 'Consumable added successfully.');
    }

    public function destroy(TestConsumable $consumable): RedirectResponse
    {
        if ($consumable->lab_id !== currentLabId()) {
            abort(404);
        }

        $consumable->delete();

        return redirect()->back()
            ->with('success', 'Consumable removed successfully.');
    }
}
