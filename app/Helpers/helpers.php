<?php

use App\Http\Middleware\DetectDomain;
use App\Models\Lab;
use App\Support\VendorStaffRoutePatterns;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (! function_exists('currentLabId')) {
    /**
     * Get the current lab ID from the request context.
     *
     * Production: staff should open the app on the lab’s configured host so
     * {@see DetectDomain} binds lab_id from the domains table.
     * The user-lab fallback exists only for local/dev or misconfigured hosts; it does not
     * apply to public website routes.
     */
    function currentLabId(): ?int
    {
        // Priority 1: Try to get from app instance (set by DetectDomain middleware)
        // This is the PRIMARY source - domain detection MUST work for SaaS
        if (app()->bound('lab_id')) {
            return app('lab_id');
        }

        // Priority 2: Fallback to config (also set by DetectDomain middleware)
        $labId = config('app.lab_id');
        if ($labId) {
            return $labId;
        }

        // Priority 3: Authenticated lab staff only — when the request host has no domain row,
        // scope vendor console routes to the user's lab_id (see VendorStaffRoutePatterns).
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->lab_id && ! $user->isSuperAdmin()) {
                $request = request();
                if (VendorStaffRoutePatterns::matches($request)) {
                    if (config('app.debug')) {
                        Log::debug('currentLabId: using user lab_id fallback (configure lab domain for production)', [
                            'path' => $request->path(),
                            'lab_id' => $user->lab_id,
                        ]);
                    }

                    return $user->lab_id;
                }
            }
        }

        return null;
    }
}

if (! function_exists('currentLab')) {
    /**
     * Get the current lab model from the request context.
     */
    function currentLab(): ?Lab
    {
        $labId = currentLabId();
        if (! $labId) {
            return null;
        }

        return Lab::find($labId);
    }
}

if (! function_exists('isLargeLab')) {
    /**
     * Check if the current lab is a large level lab.
     */
    function isLargeLab(): bool
    {
        try {
            $lab = currentLab();

            return $lab && $lab->level === 'large';
        } catch (Exception $e) {
            // If there's any error, return false (safe default)
            return false;
        }
    }
}

if (! function_exists('getDashboardRoute')) {
    /**
     * Get the appropriate dashboard route based on user role.
     */
    function getDashboardRoute(): string
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return route('super-admin.dashboard', absolute: false);
        }

        return route('dashboard', absolute: false);
    }
}
