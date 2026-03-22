<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        // Redirect super admin to super admin dashboard
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }

        $data = $this->dashboardService->getStatistics();

        return view('dashboard', $data);
    }
}
