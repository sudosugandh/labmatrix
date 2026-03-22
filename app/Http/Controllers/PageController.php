<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::where('lab_id', currentLabId())
            ->latest()
            ->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,NULL,id,lab_id,' . currentLabId(),
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $validated['lab_id'] = currentLabId();
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        // Ensure page belongs to current lab
        if ($page->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        // Ensure page belongs to current lab
        if ($page->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id . ',id,lab_id,' . currentLabId(),
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        // Ensure page belongs to current lab
        if ($page->lab_id !== currentLabId()) {
            abort(404);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
