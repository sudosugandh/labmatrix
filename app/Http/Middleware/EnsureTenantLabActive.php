<?php

namespace App\Http\Middleware;

use App\Models\Lab;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantLabActive
{
    /**
     * Block public and tenant app traffic when the lab is suspended or subscription is invalid.
     * Super admins may browse any tenant (support); auth routes stay reachable so users can sign in (dashboard will show blocked).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('super-admin*')) {
            return $next($request);
        }

        if ($request->is('webhooks/*')) {
            return $next($request);
        }

        if ($request->is('impersonation/*')) {
            return $next($request);
        }

        if (app()->environment('testing')) {
            return $next($request);
        }

        if ($request->user()?->isSuperAdmin()) {
            return $next($request);
        }

        $lab = $this->resolveLab($request);
        if (! $lab) {
            return $next($request);
        }

        if ($lab->isTenantAccessAllowed()) {
            return $next($request);
        }

        $reason = $lab->getTenantAccessBlockReason();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $reason === 'suspended'
                    ? 'This lab account is suspended. Contact support.'
                    : 'This lab subscription is inactive or expired. Contact support.',
            ], 403);
        }

        return response()
            ->view('errors.lab-unavailable', [
                'reason' => $reason,
                'labName' => $lab->name,
            ], 403);
    }

    private function resolveLab(Request $request): ?Lab
    {
        if (app()->bound('lab_id')) {
            $id = app('lab_id');

            return $id ? Lab::find($id) : null;
        }

        $uid = $request->user()?->lab_id;
        if ($uid) {
            return Lab::find($uid);
        }

        return null;
    }
}
