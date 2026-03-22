<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('lab_id', currentLabId())
            ->with('items')
            ->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['lab_id'] = currentLabId();

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    public function addItem(Request $request, Menu $menu): RedirectResponse
    {
        // Ensure menu belongs to current lab
        if ($menu->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ]);

        $validated['menu_id'] = $menu->id;
        $validated['order'] = MenuItem::where('menu_id', $menu->id)->max('order') + 1;

        MenuItem::create($validated);

        $redirect = $request->get('redirect', route('admin.menus.index'));
        return redirect($redirect)->with('success', 'Menu item added successfully.');
    }

    public function updateItem(Request $request, MenuItem $item): RedirectResponse
    {
        if ($item->menu->lab_id !== currentLabId()) {
            abort(404);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ]);
        $item->update($validated);
        $redirect = $request->get('redirect', route('admin.menus.index'));
        return redirect($redirect)->with('success', 'Menu item updated.');
    }

    public function updateItemOrder(Request $request): JsonResponse
    {
        $items = $request->input('items', []);

        foreach ($items as $order => $itemId) {
            MenuItem::where('id', $itemId)
                ->whereHas('menu', function ($query) {
                    $query->where('lab_id', currentLabId());
                })
                ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function destroyItem(Request $request, MenuItem $item): RedirectResponse
    {
        if ($item->menu->lab_id !== currentLabId()) {
            abort(404);
        }
        $item->delete();
        $redirect = $request->get('redirect', route('admin.menus.index'));
        return redirect($redirect)->with('success', 'Menu item deleted successfully.');
    }
}
