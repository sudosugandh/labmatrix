<?php

namespace App\Http\Controllers;

use App\Models\HomeVisitRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class HomeVisitRequestController extends Controller
{
    /**
     * Store a new home visit / home collection request (from popup).
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'city' => 'nullable|string|max:100',
        ]);

        $validated['lab_id'] = currentLabId();
        if (!$validated['lab_id']) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Invalid request.'], 422);
            }
            return back()->with('error', 'Invalid request.');
        }

        HomeVisitRequest::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Thank you! We will contact you shortly to guide you.',
                'success' => true,
            ]);
        }

        return back()->with('success', 'Thank you! We will contact you shortly.');
    }
}
