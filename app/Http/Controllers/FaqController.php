<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::forCurrentLab()->orderBy('sort_order')->orderBy('id')->paginate(15);
        return view('faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('faqs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['lab_id'] = currentLabId();
        $validated['is_active'] = $request->boolean('is_active');
        Faq::create($validated);
        return redirect()->route('faqs.index')->with('success', 'FAQ added.');
    }

    public function edit(Faq $faq): View|RedirectResponse
    {
        if ($faq->lab_id !== currentLabId()) {
            abort(404);
        }
        return view('faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        if ($faq->lab_id !== currentLabId()) {
            abort(404);
        }
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $faq->update($validated);
        return redirect()->route('faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        if ($faq->lab_id !== currentLabId()) {
            abort(404);
        }
        $faq->delete();
        return redirect()->route('faqs.index')->with('success', 'FAQ removed.');
    }
}
