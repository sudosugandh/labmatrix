<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::forCurrentLab()->orderBy('sort_order')->orderBy('id')->paginate(15);

        return view('testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:120',
            'quote' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_featured' => 'nullable|boolean',
        ]);
        $validated['lab_id'] = currentLabId();
        $validated['is_featured'] = $request->boolean('is_featured');
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('testimonials', 'public');
        }
        Testimonial::create($validated);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial): View|RedirectResponse
    {
        if ($testimonial->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->lab_id !== currentLabId()) {
            abort(404);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:120',
            'quote' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_featured'] = $request->boolean('is_featured');
        if ($request->hasFile('photo')) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
            $validated['photo'] = $request->file('photo')->store('testimonials', 'public');
        }
        $testimonial->update($validated);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->lab_id !== currentLabId()) {
            abort(404);
        }
        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }
        $testimonial->delete();

        return redirect()->route('testimonials.index')->with('success', 'Testimonial removed.');
    }
}
