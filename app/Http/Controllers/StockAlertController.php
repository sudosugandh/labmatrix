<?php

namespace App\Http\Controllers;

use App\Models\StockAlert;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockAlertController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockAlert::forCurrentLab()->unresolved()->with('inventoryItem');

        if ($request->filled('alert_type')) {
            $query->where('alert_type', $request->alert_type);
        }

        if ($request->filled('item_id')) {
            $query->where('inventory_item_id', $request->item_id);
        }

        $alerts = $query->latest()->paginate(20)->withQueryString();

        $labId = currentLabId();
        $stats = [
            'total' => StockAlert::where('lab_id', $labId)->unresolved()->count(),
            'low_stock' => StockAlert::where('lab_id', $labId)->unresolved()->where('alert_type', 'low_stock')->count(),
            'out_of_stock' => StockAlert::where('lab_id', $labId)->unresolved()->where('alert_type', 'out_of_stock')->count(),
            'expiring_soon' => StockAlert::where('lab_id', $labId)->unresolved()->where('alert_type', 'expiring_soon')->count(),
            'expired' => StockAlert::where('lab_id', $labId)->unresolved()->where('alert_type', 'expired')->count(),
        ];

        return view('inventory.stock-alerts.index', compact('alerts', 'stats'));
    }

    public function resolve(Request $request, StockAlert $alert): RedirectResponse
    {
        if ($alert->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'resolution_notes' => 'nullable|string|max:500',
        ]);

        $alert->resolve(auth()->id(), $validated['resolution_notes'] ?? null);

        return redirect()->route('inventory.stock-alerts.index')
            ->with('success', 'Alert resolved successfully.');
    }
}
