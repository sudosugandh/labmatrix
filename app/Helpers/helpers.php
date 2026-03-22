<?php

if (!function_exists('currentLabId')) {
    /**
     * Get the current lab ID from the request context.
     *
     * @return int|null
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
        
        // Priority 3: Only use user's lab_id for admin routes when domain detection fails
        // This is a fallback ONLY for admin panel access, not for public pages
        // Public pages MUST have domain detection working
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && $user->lab_id && !$user->isSuperAdmin()) {
                // Only allow this fallback for authenticated admin routes
                // Never use this for public routes
                $request = request();
                $isAdminRoute = $request->is([
                    'admin/*', 
                    'dashboard', 
                    'patients/*', 
                    'tests/*', 
                    'orders/*', 
                    'appointments/*', 
                    'bills/*', 
                    'reports/*', 
                    'doctors/*', 
                    'test-packages/*', 
                    'analytics',
                    'technicians/*'
                ]);
                
                if ($isAdminRoute) {
                    return $user->lab_id;
                }
            }
        }
        
        return null;
    }
}

if (!function_exists('currentLab')) {
    /**
     * Get the current lab model from the request context.
     *
     * @return \App\Models\Lab|null
     */
    function currentLab(): ?\App\Models\Lab
    {
        $labId = currentLabId();
        if (!$labId) {
            return null;
        }
        
        return \App\Models\Lab::find($labId);
    }
}

if (!function_exists('isLargeLab')) {
    /**
     * Check if the current lab is a large level lab.
     *
     * @return bool
     */
    function isLargeLab(): bool
    {
        try {
            $lab = currentLab();
            return $lab && $lab->level === 'large';
        } catch (\Exception $e) {
            // If there's any error, return false (safe default)
            return false;
        }
    }
}

if (!function_exists('getDashboardRoute')) {
    /**
     * Get the appropriate dashboard route based on user role.
     *
     * @return string
     */
    function getDashboardRoute(): string
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->isSuperAdmin()) {
            return route('super-admin.dashboard', absolute: false);
        }
        
        return route('dashboard', absolute: false);
    }
}
