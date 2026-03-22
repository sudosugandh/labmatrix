<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function index(): View
    {
        $promos = Promo::forCurrentLab()->orderBy('sort_order')->orderBy('id')->paginate(15);
        return view('promos.index', compact('promos'));
    }

    public function create(): View
    {
        return view('promos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_text' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:500',
            'valid_until' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['lab_id'] = currentLabId();
        $validated['is_active'] = $request->boolean('is_active');
        Promo::create($validated);
        return redirect()->route('promos.index')->with('success', 'Offer added.');
    }

    public function edit(Promo $promo): View|RedirectResponse
    {
        if ($promo->lab_id !== currentLabId()) {
            abort(404);
        }
        return view('promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo): RedirectResponse
    {
        if ($promo->lab_id !== currentLabId()) {
            abort(404);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_text' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:500',
            'valid_until' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $promo->update($validated);
        return redirect()->route('promos.index')->with('success', 'Offer updated.');
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        if ($promo->lab_id !== currentLabId()) {
            abort(404);
        }
        $promo->delete();
        return redirect()->route('promos.index')->with('success', 'Offer removed.');
    }
}
