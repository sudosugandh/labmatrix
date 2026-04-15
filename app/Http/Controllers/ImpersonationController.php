<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\SuperAdminAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(Request $request, Lab $lab, User $user): RedirectResponse
    {
        if ($user->lab_id !== $lab->id || $user->isSuperAdmin()) {
            abort(404);
        }

        session([
            'impersonator_id' => Auth::id(),
            'impersonation_lab_id' => $lab->id,
            'impersonation_expires_at' => now()->addHour(),
        ]);

        SuperAdminAuditLog::record(
            'lab.impersonation_started',
            'Impersonated '.$user->email.' at '.$lab->name,
            $user,
            ['lab_id' => $lab->id, 'target_user_id' => $user->id]
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'You are viewing this lab as '.$user->email.'. This session ends in one hour. Use “Exit impersonation” in the header to return to the platform console.');
    }

    public function leave(Request $request): RedirectResponse
    {
        $impersonatorId = session('impersonator_id');
        $labId = session('impersonation_lab_id');
        if (! $impersonatorId) {
            return redirect()->route('dashboard');
        }

        session()->forget(['impersonator_id', 'impersonation_lab_id', 'impersonation_expires_at']);

        Auth::loginUsingId((int) $impersonatorId);
        $request->session()->regenerate();

        SuperAdminAuditLog::record(
            'lab.impersonation_ended',
            'Stopped impersonation'.($labId ? ' for lab #'.$labId : ''),
            null,
            ['lab_id' => $labId]
        );

        if ($labId) {
            return redirect()->route('super-admin.labs.show', $labId)
                ->with('success', 'You are back in the platform console.');
        }

        return redirect()->route('super-admin.dashboard')
            ->with('success', 'You are back in the platform console.');
    }
}
