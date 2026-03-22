<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsImageController extends Controller
{
    /**
     * Upload an image for TinyMCE / CMS content. Returns public URL.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB
        ]);

        $file = $request->file('file');
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('cms', $name, 'public');
        $url = asset('storage/' . $path);

        return response()->json(['url' => $url]);
    }
}
