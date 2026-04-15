<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ExpireImpersonation
{
    /**
     * End lab impersonation when the time limit is reached; restore the super admin session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('impersonator_id')) {
            return $next($request);
        }

        if ($request->is('impersonation/*')) {
            return $next($request);
        }

        $expires = session('impersonation_expires_at');
        if ($expires && now()->greaterThan($expires)) {
            $impersonatorId = session('impersonator_id');
            $labId = session('impersonation_lab_id');
            session()->forget(['impersonator_id', 'impersonation_lab_id', 'impersonation_expires_at']);
            Auth::loginUsingId((int) $impersonatorId);
            $request->session()->regenerate();

            $target = $labId
                ? redirect()->route('super-admin.labs.show', $labId)
                : redirect()->route('super-admin.dashboard');

            return $target->with('warning', 'Impersonation session expired.');
        }

        return $next($request);
    }
}
