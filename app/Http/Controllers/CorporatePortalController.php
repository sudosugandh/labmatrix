<?php

namespace App\Http\Controllers;

use App\Models\CorporateAccount;
use App\Models\HealthCamp;
use App\Models\Menu;
use App\Models\Report;
use App\Models\TestOrder;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CorporatePortalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            (new Middleware('guest:corporate'))->only(['showLogin', 'login']),
            (new Middleware('auth:corporate'))->except(['showLogin', 'login']),
        ];
    }

    public function showLogin(): View
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', currentLabId())->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        return view('website.corporate-portal.login', compact('settings', 'menuItems'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'required|string|max:32',
            'password' => 'required|string',
        ]);

        $labId = currentLabId();
        if (! $labId) {
            return back()->withErrors(['phone' => 'Lab context missing.'])->onlyInput('phone');
        }

        $account = CorporateAccount::where('lab_id', $labId)
            ->where('phone', $request->phone)
            ->where('is_active', true)
            ->first();

        if (! $account || ! $account->password || ! Hash::check($request->password, $account->password)) {
            return back()->withErrors(['phone' => 'These credentials do not match our records.'])->onlyInput('phone');
        }

        Auth::guard('corporate')->login($account, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('corporate.portal.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('corporate')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('corporate.portal.login');
    }

    public function dashboard(): View
    {
        $account = Auth::guard('corporate')->user();
        $labId = currentLabId();
        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', $labId)->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        $camps = HealthCamp::where('lab_id', $labId)
            ->where('corporate_account_id', $account->id)
            ->withCount([
                'testOrders',
            ])
            ->latest('camp_date')
            ->paginate(15);

        foreach ($camps as $camp) {
            $camp->reports_ready_count = Report::where('lab_id', $labId)
                ->where('workflow_status', 'released')
                ->where('status', 'ready')
                ->whereHas('testOrder', fn ($q) => $q->where('health_camp_id', $camp->id))
                ->count();
        }

        return view('website.corporate-portal.dashboard', compact('account', 'settings', 'menuItems', 'camps'));
    }

    public function camp(HealthCamp $healthCamp): View
    {
        $account = Auth::guard('corporate')->user();
        $labId = currentLabId();
        abort_if($healthCamp->lab_id !== $labId || (int) $healthCamp->corporate_account_id !== (int) $account->id, 404);

        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', $labId)->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        $orders = TestOrder::where('lab_id', $labId)
            ->where('health_camp_id', $healthCamp->id)
            ->with(['patient', 'test', 'report'])
            ->latest()
            ->paginate(30);

        $uniquePatients = TestOrder::where('lab_id', $labId)
            ->where('health_camp_id', $healthCamp->id)
            ->pluck('patient_id')
            ->filter()
            ->unique()
            ->count();

        return view('website.corporate-portal.camp', compact('account', 'settings', 'menuItems', 'healthCamp', 'orders', 'uniquePatients'));
    }

    public function campExport(HealthCamp $healthCamp): StreamedResponse
    {
        $account = Auth::guard('corporate')->user();
        $labId = currentLabId();
        abort_if($healthCamp->lab_id !== $labId || (int) $healthCamp->corporate_account_id !== (int) $account->id, 404);

        $orders = TestOrder::where('lab_id', $labId)
            ->where('health_camp_id', $healthCamp->id)
            ->with(['patient', 'test'])
            ->orderBy('id')
            ->get();

        $filename = 'camp-'.$healthCamp->id.'-participants-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($orders) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Patient', 'Phone', 'Test', 'Order status', 'Report status']);
            foreach ($orders as $o) {
                fputcsv($out, [
                    $o->patient?->name,
                    $o->patient?->phone,
                    $o->test?->name,
                    $o->report_status,
                    $o->report ? 'available' : 'pending',
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
