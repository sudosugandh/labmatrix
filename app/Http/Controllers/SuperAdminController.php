<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Lab;
use App\Models\LabSupportNote;
use App\Models\Patient;
use App\Models\Report;
use App\Models\SuperAdminAuditLog;
use App\Models\TestOrder;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Services\LabQuota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    /**
     * Display super admin dashboard.
     */
    public function dashboard(): View
    {
        $stats = [
            'total_labs' => Lab::count(),
            'active_labs' => Lab::where('status', 'active')->orWhereNull('status')->count(),
            'suspended_labs' => Lab::where('status', 'suspended')->count(),
            'total_users' => User::where('role', '!=', 'super_admin')->count(),
            'total_patients' => Patient::count(),
            'total_orders' => TestOrder::count(),
            'total_reports' => Report::count(),
            'large_labs' => Lab::where('level', 'large')->count(),
            'small_labs' => Lab::where('level', 'small')->count(),
            'total_domains' => Domain::count(),
            'active_domains' => Domain::where('is_active', true)->count(),
        ];

        // Recent labs with more details
        $recentLabs = Lab::withCount(['users', 'domains', 'patients', 'testOrders'])
            ->latest()
            ->take(10)
            ->get();

        // Labs by level
        $labsByLevel = [
            'large' => Lab::where('level', 'large')->count(),
            'small' => Lab::where('level', 'small')->count(),
        ];

        // Labs by status
        $labsByStatus = [
            'active' => Lab::where('status', 'active')->orWhereNull('status')->count(),
            'suspended' => Lab::where('status', 'suspended')->count(),
        ];

        // Top labs by activity
        $topLabs = Lab::withCount(['testOrders', 'patients'])
            ->orderBy('test_orders_count', 'desc')
            ->take(5)
            ->get();

        $growthChartConfig = $this->buildPlatformGrowthChartConfig();

        return view('super-admin.dashboard', compact('stats', 'recentLabs', 'labsByLevel', 'labsByStatus', 'topLabs', 'growthChartConfig'));
    }

    /**
     * Monthly counts for the super-admin dashboard chart (last 12 months).
     *
     * @return array<string, mixed> Chart.js-compatible config
     */
    private function buildPlatformGrowthChartConfig(): array
    {
        $labels = [];
        $orders = [];
        $reports = [];
        $patients = [];
        $labs = [];

        for ($i = 11; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = Carbon::now()->subMonths($i)->endOfMonth();
            $labels[] = $start->format('M \'y');
            $orders[] = TestOrder::whereBetween('created_at', [$start, $end])->count();
            $reports[] = Report::whereBetween('created_at', [$start, $end])->count();
            $patients[] = Patient::whereBetween('created_at', [$start, $end])->count();
            $labs[] = Lab::whereBetween('created_at', [$start, $end])->count();
        }

        return [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Orders',
                        'data' => $orders,
                        'borderColor' => 'rgb(124, 58, 237)',
                        'backgroundColor' => 'rgba(124, 58, 237, 0.12)',
                        'fill' => true,
                        'tension' => 0.35,
                        'borderWidth' => 2,
                        'pointRadius' => 3,
                        'pointHoverRadius' => 5,
                    ],
                    [
                        'label' => 'Reports',
                        'data' => $reports,
                        'borderColor' => 'rgb(13, 148, 136)',
                        'backgroundColor' => 'rgba(13, 148, 136, 0.08)',
                        'fill' => true,
                        'tension' => 0.35,
                        'borderWidth' => 2,
                        'pointRadius' => 3,
                        'pointHoverRadius' => 5,
                    ],
                    [
                        'label' => 'New patients',
                        'data' => $patients,
                        'borderColor' => 'rgb(234, 88, 12)',
                        'backgroundColor' => 'rgba(234, 88, 12, 0.06)',
                        'fill' => true,
                        'tension' => 0.35,
                        'borderWidth' => 2,
                        'pointRadius' => 3,
                        'pointHoverRadius' => 5,
                    ],
                    [
                        'label' => 'New labs',
                        'data' => $labs,
                        'borderColor' => 'rgb(100, 116, 139)',
                        'backgroundColor' => 'rgba(100, 116, 139, 0.06)',
                        'fill' => false,
                        'tension' => 0.35,
                        'borderWidth' => 2,
                        'borderDash' => [6, 4],
                        'pointRadius' => 2,
                        'pointHoverRadius' => 4,
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => [
                    'intersect' => false,
                    'mode' => 'index',
                ],
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                        'align' => 'end',
                        'labels' => [
                            'usePointStyle' => true,
                            'boxWidth' => 8,
                            'boxHeight' => 8,
                            'padding' => 16,
                            'color' => '#475569',
                            'font' => ['size' => 11, 'family' => "'Inter', ui-sans-serif, system-ui, sans-serif"],
                        ],
                    ],
                    'tooltip' => [
                        'backgroundColor' => 'rgba(15, 23, 42, 0.92)',
                        'padding' => 12,
                        'cornerRadius' => 8,
                    ],
                ],
                'scales' => [
                    'x' => [
                        'grid' => [
                            'display' => true,
                            'color' => 'rgba(148, 163, 184, 0.25)',
                            'borderDash' => [4, 4],
                        ],
                        'ticks' => [
                            'color' => '#64748b',
                            'font' => ['size' => 11],
                        ],
                    ],
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'precision' => 0,
                            'color' => '#64748b',
                            'font' => ['size' => 11],
                        ],
                        'grid' => [
                            'color' => 'rgba(148, 163, 184, 0.2)',
                            'borderDash' => [4, 4],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * All domains across labs (platform view).
     */
    public function domains(Request $request): View
    {
        $query = Domain::query()->with('lab')->orderBy('domain');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('domain', 'like', '%'.$search.'%')
                    ->orWhereHas('lab', function ($lq) use ($search) {
                        $lq->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('active')) {
            if ($request->active === '1') {
                $query->where('is_active', true);
            } elseif ($request->active === '0') {
                $query->where('is_active', false);
            }
        }

        $domains = $query->paginate(20)->withQueryString();

        return view('super-admin.domains.index', compact('domains'));
    }

    /**
     * All users across labs (platform view).
     */
    public function users(Request $request): View
    {
        $query = User::query()->with('lab')->orderByDesc('created_at');

        if (! $request->boolean('include_super')) {
            $query->where('role', '!=', 'super_admin');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('role')) {
            $allowedRoles = ['admin', 'staff', 'user', 'super_admin'];
            if (in_array($request->role, $allowedRoles, true)) {
                $query->where('role', $request->role);
            }
        }

        if ($request->filled('lab_search')) {
            $labSearch = $request->lab_search;
            $query->whereHas('lab', function ($lq) use ($labSearch) {
                $lq->where('name', 'like', '%'.$labSearch.'%');
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('super-admin.users.index', compact('users'));
    }

    /**
     * Super-admin audit trail (mutations and sensitive reads).
     */
    public function activity(Request $request): View
    {
        $query = SuperAdminAuditLog::query()
            ->with('user:id,name,email')
            ->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', '%'.$s.'%')
                    ->orWhere('action', 'like', '%'.$s.'%');
            });
        }

        $logs = $query->paginate(30)->withQueryString();

        $actionChoices = SuperAdminAuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('super-admin.activity.index', compact('logs', 'actionChoices'));
    }

    /**
     * Export labs data.
     */
    public function exportLabs(Request $request)
    {
        SuperAdminAuditLog::record('labs.exported', 'Downloaded platform labs CSV export');

        $labs = Lab::withCount(['users', 'domains', 'patients', 'testOrders', 'reports'])
            ->withMax('testOrders', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        $filename = 'labs_export_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($labs) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Address', 'Level', 'Lab Status',
                'Subscription Plan', 'Subscription Status', 'Sub. End Date',
                'Users', 'Patients', 'Orders', 'Reports', 'Domains',
                'Last Order At', 'Created At',
            ]);

            // Data
            foreach ($labs as $lab) {
                fputcsv($file, [
                    $lab->id,
                    $lab->name,
                    $lab->email,
                    $lab->phone,
                    $lab->address,
                    $lab->level,
                    $lab->status ?? 'active',
                    $lab->subscription_plan ?? 'trial',
                    $lab->subscription_status ?? 'active',
                    $lab->subscription_end_date?->format('Y-m-d') ?? '',
                    $lab->users_count,
                    $lab->patients_count,
                    $lab->test_orders_count,
                    $lab->reports_count,
                    $lab->domains_count,
                    $lab->test_orders_max_created_at
                        ? Carbon::parse($lab->test_orders_max_created_at)->format('Y-m-d H:i:s')
                        : '',
                    $lab->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a listing of all labs.
     */
    public function labs(Request $request): View
    {
        $query = Lab::withCount(['users', 'domains', 'patients', 'testOrders', 'reports'])
            ->withMax('testOrders', 'created_at');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by lab operational status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by subscription plan
        if ($request->filled('subscription_plan')) {
            $query->where('subscription_plan', $request->subscription_plan);
        }

        // Filter by subscription billing status
        if ($request->filled('subscription_status')) {
            $query->where('subscription_status', $request->subscription_status);
        }

        $labs = $query->latest()->paginate(15)->withQueryString();

        return view('super-admin.labs.index', compact('labs'));
    }

    /**
     * Show the form for creating a new lab.
     */
    public function createLab(): View
    {
        return view('super-admin.labs.create');
    }

    /**
     * Store a newly created lab.
     */
    public function storeLab(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:labs,email|unique:users,email',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'level' => 'required|in:small,large',
            'status' => 'nullable|in:active,suspended',
            'domain' => 'nullable|string|max:255|unique:domains,domain',
        ]);

        $lab = Lab::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'level' => $validated['level'],
            'status' => $validated['status'] ?? 'active',
            'max_users' => null,
            'max_storage_mb' => null,
        ]);

        // Create default domain if provided
        if (! empty($validated['domain'])) {
            Domain::create([
                'lab_id' => $lab->id,
                'domain' => $validated['domain'],
                'is_active' => true,
            ]);
        }

        // Default admin: random password (never shown); user sets password via email link
        $user = User::create([
            'name' => $validated['name'].' Admin',
            'email' => $validated['email'],
            'password' => Str::password(48),
            'lab_id' => $lab->id,
            'role' => 'admin',
        ]);

        $resetStatus = Password::sendResetLink(['email' => $user->email]);

        SuperAdminAuditLog::record('lab.created', 'Created lab: '.$lab->name, $lab, [
            'level' => $lab->level,
            'initial_domain' => $validated['domain'] ?? null,
            'default_admin_user_id' => $user->id,
            'password_reset_email' => $resetStatus === Password::RESET_LINK_SENT,
        ]);

        $success = $resetStatus === Password::RESET_LINK_SENT
            ? 'Lab created. A password setup link was sent to '.$validated['email'].'.'
            : 'Lab created. The admin account is ready — configure mail (MAIL_*) and use “Forgot password” on the lab login page so they can set a password (setup email could not be sent).';

        return redirect()->route('super-admin.labs.show', $lab)
            ->with('success', $success);
    }

    /**
     * Display the specified lab.
     */
    public function showLab(Lab $lab): View
    {
        $lab->loadCount(['users', 'domains', 'patients', 'testOrders', 'reports', 'supportNotes']);
        $lab->load(['domains', 'users', 'supportNotes' => function ($query) {
            $query->with('creator')->latest();
        }]);

        // Get website settings for this lab
        $settings = WebsiteSetting::where('lab_id', $lab->id)->first();

        // Get activity data
        $activityData = $this->getLabActivity($lab);

        $planUsage = [
            'users_current' => $lab->users()->count(),
            'users_max' => $lab->effectiveMaxUsers(),
            'storage_mb_used' => round($lab->estimatedStorageUsageBytes() / 1048576, 1),
            'storage_mb_max' => $lab->effectiveMaxStorageMb(),
        ];

        return view('super-admin.labs.show', compact('lab', 'settings', 'activityData', 'planUsage'));
    }

    /**
     * Get lab activity data.
     */
    private function getLabActivity(Lab $lab): array
    {
        // Get most recent user activity (by user creation or any activity)
        $recentUser = User::where('lab_id', $lab->id)
            ->latest()
            ->first();

        // Get recent orders
        $recentOrders = TestOrder::where('lab_id', $lab->id)
            ->latest()
            ->take(5)
            ->get();

        // Get monthly stats
        $monthlyOrders = TestOrder::where('lab_id', $lab->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $monthlyReports = Report::where('lab_id', $lab->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get last 7 days activity
        $last7DaysOrders = TestOrder::where('lab_id', $lab->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $last7DaysReports = Report::where('lab_id', $lab->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return [
            'recent_user' => $recentUser?->name,
            'recent_user_date' => $recentUser?->created_at,
            'recent_orders' => $recentOrders,
            'monthly_orders' => $monthlyOrders,
            'monthly_reports' => $monthlyReports,
            'last_7_days_orders' => $last7DaysOrders,
            'last_7_days_reports' => $last7DaysReports,
            'total_orders' => $lab->test_orders_count ?? 0,
            'total_reports' => $lab->reports_count ?? 0,
            'lab_created_at' => $lab->created_at,
        ];
    }

    /**
     * Update lab website settings.
     */
    public function updateLabSettings(Request $request, Lab $lab): RedirectResponse
    {
        $settings = WebsiteSetting::firstOrCreate(
            ['lab_id' => $lab->id],
            [
                'brand_name' => $lab->name,
                'primary_color' => '#3B82F6',
                'theme_preset' => 'violet',
            ]
        );

        $validated = $request->validate([
            'brand_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $settings->update($validated);

        if (! empty($validated['brand_name'])) {
            $lab->update(['name' => $validated['brand_name']]);
        }

        SuperAdminAuditLog::record('lab.settings_updated', 'Updated website/branding settings for '.$lab->name, $lab);

        return redirect()->back()
            ->with('success', 'Lab settings updated successfully.');
    }

    /**
     * Update lab feature flags.
     */
    public function updateLabFeatures(Request $request, Lab $lab): RedirectResponse
    {
        $settings = WebsiteSetting::firstOrCreate(
            ['lab_id' => $lab->id],
            [
                'brand_name' => $lab->name,
                'online_booking_enabled' => true,
                'online_reports_enabled' => true,
                'notifications_enabled' => true,
                'analytics_enabled' => true,
                'payments_enabled' => false,
            ]
        );

        $validated = $request->validate([
            'online_booking_enabled' => 'nullable|boolean',
            'online_reports_enabled' => 'nullable|boolean',
            'notifications_enabled' => 'nullable|boolean',
            'analytics_enabled' => 'nullable|boolean',
            'payments_enabled' => 'nullable|boolean',
        ]);

        $settings->update($validated);

        SuperAdminAuditLog::record('lab.features_updated', 'Updated feature flags for '.$lab->name, $lab, $validated);

        return redirect()->back()
            ->with('success', 'Lab features updated successfully.');
    }

    /**
     * Show the form for editing the specified lab.
     */
    public function editLab(Lab $lab): View
    {
        return view('super-admin.labs.edit', compact('lab'));
    }

    /**
     * Update the specified lab.
     */
    public function updateLab(Request $request, Lab $lab): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:labs,email,'.$lab->id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'level' => 'required|in:small,large',
            'status' => 'nullable|in:active,suspended',
            'max_users' => 'nullable|integer|min:1|max:999999',
            'max_storage_mb' => 'nullable|integer|min:1|max:999999',
        ]);

        $lab->update([
            ...collect($validated)->except(['max_users', 'max_storage_mb'])->all(),
            'max_users' => $request->filled('max_users') ? (int) $request->max_users : null,
            'max_storage_mb' => $request->filled('max_storage_mb') ? (int) $request->max_storage_mb : null,
        ]);

        SuperAdminAuditLog::record('lab.updated', 'Updated lab profile: '.$lab->name, $lab);

        return redirect()->route('super-admin.labs.show', $lab)
            ->with('success', 'Lab updated successfully.');
    }

    /**
     * Delete a lab.
     */
    public function destroyLab(Lab $lab): RedirectResponse
    {
        $labName = $lab->name;
        $labId = $lab->id;
        SuperAdminAuditLog::record('lab.deleted', 'Deleted lab: '.$labName, null, ['lab_id' => $labId, 'name' => $labName]);
        $lab->delete();

        return redirect()->route('super-admin.labs.index')
            ->with('success', "Lab '{$labName}' deleted successfully.");
    }

    /**
     * Suspend a lab.
     */
    public function suspendLab(Lab $lab): RedirectResponse
    {
        $lab->update(['status' => 'suspended']);

        SuperAdminAuditLog::record('lab.suspended', 'Suspended lab: '.$lab->name, $lab);

        return redirect()->back()
            ->with('success', 'Lab suspended successfully.');
    }

    /**
     * Activate a lab.
     */
    public function activateLab(Lab $lab): RedirectResponse
    {
        $lab->update(['status' => 'active']);

        SuperAdminAuditLog::record('lab.activated', 'Activated lab: '.$lab->name, $lab);

        return redirect()->back()
            ->with('success', 'Lab activated successfully.');
    }

    /**
     * Update lab level.
     */
    public function updateLabLevel(Request $request, Lab $lab): RedirectResponse
    {
        $validated = $request->validate([
            'level' => 'required|in:small,large',
        ]);

        $lab->update($validated);

        SuperAdminAuditLog::record('lab.level_changed', 'Changed lab level to '.$validated['level'].' for '.$lab->name, $lab, ['level' => $validated['level']]);

        return redirect()->back()
            ->with('success', 'Lab level updated successfully.');
    }

    /**
     * Store a new domain for a lab.
     */
    public function storeDomain(Request $request, Lab $lab): RedirectResponse
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:domains,domain',
            'is_active' => 'nullable|boolean',
        ]);

        $domain = Domain::create([
            'lab_id' => $lab->id,
            'domain' => $validated['domain'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        SuperAdminAuditLog::record('domain.created', 'Added domain '.$domain->domain.' to '.$lab->name, $domain, ['lab_id' => $lab->id]);

        return redirect()->back()
            ->with('success', 'Domain added successfully.');
    }

    /**
     * Update a domain.
     */
    public function updateDomain(Request $request, Lab $lab, Domain $domain): RedirectResponse
    {
        if ($domain->lab_id !== $lab->id) {
            abort(404);
        }

        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:domains,domain,'.$domain->id,
            'is_active' => 'nullable|boolean',
        ]);

        $domain->update($validated);

        SuperAdminAuditLog::record('domain.updated', 'Updated domain '.$domain->domain.' ('.$lab->name.')', $domain, ['lab_id' => $lab->id]);

        return redirect()->back()
            ->with('success', 'Domain updated successfully.');
    }

    /**
     * Delete a domain.
     */
    public function destroyDomain(Lab $lab, Domain $domain): RedirectResponse
    {
        if ($domain->lab_id !== $lab->id) {
            abort(404);
        }

        $host = $domain->domain;
        SuperAdminAuditLog::record('domain.deleted', 'Removed domain '.$host.' from '.$lab->name, null, ['lab_id' => $lab->id, 'domain' => $host]);
        $domain->delete();

        return redirect()->back()
            ->with('success', 'Domain deleted successfully.');
    }

    /**
     * Store a new user for a lab.
     */
    public function storeUser(Request $request, Lab $lab): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        LabQuota::assertCanAddUser($lab);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'lab_id' => $lab->id,
            'role' => $validated['role'],
        ]);

        SuperAdminAuditLog::record('user.created', 'Created user '.$newUser->email.' for '.$lab->name, $newUser, ['lab_id' => $lab->id, 'role' => $validated['role']]);

        return redirect()->back()
            ->with('success', 'User created successfully.');
    }

    /**
     * Update a user.
     */
    public function updateUser(Request $request, Lab $lab, User $user): RedirectResponse
    {
        if ($user->lab_id !== $lab->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

        $meta = ['lab_id' => $lab->id];
        if (! empty($validated['password'])) {
            $meta['password_changed'] = true;
        }
        SuperAdminAuditLog::record('user.updated', 'Updated user '.$user->email.' ('.$lab->name.')', $user, $meta);

        return redirect()->back()
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroyUser(Lab $lab, User $user): RedirectResponse
    {
        if ($user->lab_id !== $lab->id) {
            abort(404);
        }

        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return redirect()->back()
                ->with('error', 'Cannot delete super admin user.');
        }

        $email = $user->email;
        SuperAdminAuditLog::record('user.deleted', 'Deleted user '.$email.' from '.$lab->name, null, ['lab_id' => $lab->id, 'email' => $email]);
        $user->delete();

        return redirect()->back()
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Update lab subscription/billing.
     */
    public function updateLabSubscription(Request $request, Lab $lab): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_plan' => 'required|in:trial,basic,pro,enterprise',
            'subscription_status' => 'required|in:active,expired,suspended,cancelled',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date|after_or_equal:subscription_start_date',
            'monthly_fee' => 'nullable|numeric|min:0',
            'billing_notes' => 'nullable|string',
            'stripe_customer_id' => 'nullable|string|max:255',
            'stripe_subscription_id' => 'nullable|string|max:255',
        ]);

        $validated['stripe_customer_id'] = $validated['stripe_customer_id'] ?: null;
        $validated['stripe_subscription_id'] = $validated['stripe_subscription_id'] ?: null;

        $lab->update($validated);

        SuperAdminAuditLog::record('lab.subscription_updated', 'Updated subscription for '.$lab->name, $lab, [
            'subscription_plan' => $validated['subscription_plan'],
            'subscription_status' => $validated['subscription_status'],
        ]);

        return redirect()->back()
            ->with('success', 'Subscription information updated successfully.');
    }

    /**
     * Store a support note for a lab.
     */
    public function storeSupportNote(Request $request, Lab $lab): RedirectResponse
    {
        $validated = $request->validate([
            'note' => 'required|string',
            'type' => 'required|in:general,support,billing,technical,other',
            'is_important' => 'nullable|boolean',
        ]);

        $note = LabSupportNote::create([
            'lab_id' => $lab->id,
            'created_by' => Auth::id(),
            'note' => $validated['note'],
            'type' => $validated['type'],
            'is_important' => $request->has('is_important') && $request->is_important == '1',
        ]);

        SuperAdminAuditLog::record('support_note.created', 'Added '.$validated['type'].' support note for '.$lab->name, $note, ['lab_id' => $lab->id]);

        return redirect()->back()
            ->with('success', 'Support note added successfully.');
    }

    /**
     * Delete a support note.
     */
    public function destroySupportNote(Lab $lab, LabSupportNote $note): RedirectResponse
    {
        if ($note->lab_id !== $lab->id) {
            abort(404);
        }

        SuperAdminAuditLog::record('support_note.deleted', 'Deleted support note #'.$note->id.' for '.$lab->name, null, ['lab_id' => $lab->id, 'note_id' => $note->id]);
        $note->delete();

        return redirect()->back()
            ->with('success', 'Support note deleted successfully.');
    }

    /**
     * Export all lab data.
     */
    public function exportLabData(Lab $lab)
    {
        // Only load non-sensitive data
        $lab->load([
            'users' => function ($query) {
                $query->select('id', 'name', 'email', 'role', 'lab_id', 'created_at', 'updated_at');
            },
            'domains',
            'supportNotes' => function ($query) {
                $query->with('creator:id,name');
            },
        ]);

        // Get counts without loading actual data
        $lab->loadCount(['patients', 'testOrders', 'reports']);

        $settings = WebsiteSetting::where('lab_id', $lab->id)->first();

        $filename = 'lab_'.$lab->id.'_export_'.date('Y-m-d_His').'.json';

        // Prepare lab data excluding sensitive fields
        $labData = $lab->only([
            'id', 'name', 'email', 'phone', 'address', 'level', 'status',
            'subscription_plan', 'subscription_status', 'subscription_start_date',
            'subscription_end_date', 'monthly_fee', 'billing_notes',
            'created_at', 'updated_at',
        ]);

        $data = [
            'lab' => $labData,
            'settings' => $settings?->only([
                'lab_id', 'site_name', 'site_description', 'contact_email',
                'contact_phone', 'address', 'logo', 'favicon', 'primary_color',
                'secondary_color', 'created_at', 'updated_at',
            ]),
            'users' => $lab->users->map(function ($user) {
                return $user->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']);
            }),
            'domains' => $lab->domains->toArray(),
            'support_notes' => $lab->supportNotes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'note' => $note->note,
                    'type' => $note->type,
                    'is_important' => $note->is_important,
                    'created_by' => $note->creator?->name,
                    'created_at' => $note->created_at,
                ];
            }),
            'statistics' => [
                'patients_count' => $lab->patients_count,
                'test_orders_count' => $lab->test_orders_count,
                'reports_count' => $lab->reports_count,
                'users_count' => $lab->users->count(),
                'domains_count' => $lab->domains->count(),
                'support_notes_count' => $lab->supportNotes->count(),
            ],
            'export_metadata' => [
                'exported_at' => now()->toDateTimeString(),
                'exported_by' => Auth::user()->name,
                'exported_by_id' => Auth::user()->id,
                'note' => 'Patient data, orders, and reports are excluded for privacy reasons. Only counts are included.',
            ],
        ];

        SuperAdminAuditLog::record('lab.data_exported', 'Downloaded JSON export for lab: '.$lab->name, $lab);

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ], JSON_PRETTY_PRINT);
    }
}
