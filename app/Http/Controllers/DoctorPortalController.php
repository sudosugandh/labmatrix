<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorCommission;
use App\Models\Menu;
use App\Models\Report;
use App\Models\TestOrder;
use App\Models\WebsiteSetting;
use App\Support\PhoneDigits;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DoctorPortalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            (new Middleware('guest:doctor'))->only(['showLogin', 'login']),
            (new Middleware('auth:doctor'))->except(['showLogin', 'login']),
        ];
    }

    public function showLogin(): View
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', currentLabId())->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        return view('website.doctor-portal.login', compact('settings', 'menuItems'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $labId = currentLabId();
        if (! $labId) {
            return back()->withErrors(['phone' => 'Lab context missing. Open this page from your lab website.'])->onlyInput('phone');
        }

        $identifier = trim((string) $request->phone);

        if (str_contains($identifier, '@')) {
            $request->validate([
                'phone' => 'email',
            ]);

            $doctor = Doctor::query()
                ->where('lab_id', $labId)
                ->where('is_active', true)
                ->whereNotNull('email')
                ->whereRaw('LOWER(TRIM(email)) = ?', [strtolower($identifier)])
                ->first();
        } else {
            $normalized = PhoneDigits::normalize($identifier);
            if (! $normalized) {
                return back()->withErrors(['phone' => 'Enter a valid phone number or email.'])->onlyInput('phone');
            }

            $doctor = Doctor::query()
                ->where('lab_id', $labId)
                ->where('is_active', true)
                ->get()
                ->first(fn (Doctor $d) => PhoneDigits::normalize((string) ($d->phone ?? '')) === $normalized);
        }

        if (! $doctor || ! $doctor->password || ! Hash::check($request->password, $doctor->password)) {
            return back()->withErrors(['phone' => 'These credentials do not match our records.'])->onlyInput('phone');
        }

        Auth::guard('doctor')->login($doctor, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('doctor.portal.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('doctor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('doctor.portal.login');
    }

    public function dashboard(): View
    {
        $doctor = Auth::guard('doctor')->user();
        $labId = currentLabId();
        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', $labId)->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        $stats = [
            'pending_commission' => DoctorCommission::where('doctor_id', $doctor->id)->where('lab_id', $labId)->where('status', 'pending')->sum('commission_amount'),
            'referrals_count' => TestOrder::where('doctor_id', $doctor->id)->where('lab_id', $labId)->count(),
            'reports_ready' => Report::where('lab_id', $labId)
                ->where('workflow_status', 'released')
                ->where('status', 'ready')
                ->whereHas('testOrder', fn ($q) => $q->where('doctor_id', $doctor->id))
                ->count(),
        ];

        return view('website.doctor-portal.dashboard', compact('doctor', 'settings', 'menuItems', 'stats'));
    }

    public function commissions(Request $request): View
    {
        $doctor = Auth::guard('doctor')->user();
        $labId = currentLabId();
        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', $labId)->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        $query = DoctorCommission::where('doctor_id', $doctor->id)
            ->where('lab_id', $labId)
            ->with(['bill', 'testOrder.test']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $commissions = $query->latest()->paginate(20)->withQueryString();

        return view('website.doctor-portal.commissions', compact('doctor', 'settings', 'menuItems', 'commissions'));
    }

    public function referrals(Request $request): View
    {
        $doctor = Auth::guard('doctor')->user();
        $labId = currentLabId();
        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', $labId)->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        $orders = TestOrder::where('doctor_id', $doctor->id)
            ->where('lab_id', $labId)
            ->with(['patient', 'test', 'package', 'bill', 'appointment'])
            ->latest()
            ->paginate(20);

        return view('website.doctor-portal.referrals', compact('doctor', 'settings', 'menuItems', 'orders'));
    }

    public function reports(Request $request): View
    {
        $doctor = Auth::guard('doctor')->user();
        $labId = currentLabId();
        $settings = WebsiteSetting::getForCurrentLab();
        $menu = Menu::where('lab_id', $labId)->where('name', 'Main Menu')->first();
        $menuItems = $menu ? $menu->items : collect();

        $reports = Report::where('lab_id', $labId)
            ->where('workflow_status', 'released')
            ->where('status', 'ready')
            ->whereHas('testOrder', fn ($q) => $q->where('doctor_id', $doctor->id))
            ->with(['patient', 'testOrder.test', 'testOrder.appointment'])
            ->latest()
            ->paginate(20);

        return view('website.doctor-portal.reports', compact('doctor', 'settings', 'menuItems', 'reports'));
    }
}
