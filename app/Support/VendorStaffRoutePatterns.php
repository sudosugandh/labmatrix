<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Path patterns for the vendor (lab) staff console: routes using auth + verified
 * in routes/web.php (main dashboard group and admin/*), excluding super-admin and public site.
 *
 * Used by currentLabId() fallback when DetectDomain did not bind lab_id — production
 * should still use the lab’s hostname so domain resolution is the primary source.
 */
final class VendorStaffRoutePatterns
{
    public static function matches(Request $request): bool
    {
        return $request->is(self::patterns());
    }

    /**
     * @return list<string>
     */
    public static function patterns(): array
    {
        return [
            'admin/*',
            'dashboard',
            'patients*',
            'tests*',
            'test-consumables*',
            'doctors*',
            'doctor-commissions*',
            'corporate-accounts*',
            'health-camps*',
            'test-packages*',
            'technicians*',
            'orders*',
            'reports*',
            'report-templates*',
            'testimonials*',
            'faqs*',
            'promos*',
            'bills*',
            'payments*',
            'appointments*',
            'appointment-slots*',
            'inventory*',
            'analytics*',
            'export*',
            'notifications*',
            'report-builder*',
            'profile',
            'profile/*',
        ];
    }
}
