<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\User;
use App\Models\Patient;
use App\Models\TestOrder;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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
            'total_domains' => \App\Models\Domain::count(),
            'active_domains' => \App\Models\Domain::where('is_active', true)->count(),
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

        return view('super-admin.dashboard', compact('stats', 'recentLabs', 'labsByLevel', 'labsByStatus', 'topLabs'));
    }

    /**
     * Export labs data.
     */
    public function exportLabs(Request $request)
    {
        $labs = Lab::withCount(['users', 'domains', 'patients', 'testOrders', 'reports'])
            ->withMax('testOrders', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        $filename = 'labs_export_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($labs) {
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
                        ? \Illuminate\Support\Carbon::parse($lab->test_orders_max_created_at)->format('Y-m-d H:i:s')
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
            $query->where(function($q) use ($search) {
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
            'email' => 'required|email|max:255|unique:labs,email',
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
        ]);

        // Create default domain if provided
        if (!empty($validated['domain'])) {
            \App\Models\Domain::create([
                'lab_id' => $lab->id,
                'domain' => $validated['domain'],
                'is_active' => true,
            ]);
        }

        // Create default admin user
        $user = User::create([
            'name' => $validated['name'] . ' Admin',
            'email' => $validated['email'],
            'password' => bcrypt('password'), // Default password, should be changed
            'lab_id' => $lab->id,
            'role' => 'admin',
        ]);

        return redirect()->route('super-admin.labs.show', $lab)
            ->with('success', 'Lab created successfully. Default admin user created with email: ' . $validated['email'] . ' and password: password');
    }

    /**
     * Display the specified lab.
     */
    public function showLab(Lab $lab): View
    {
        $lab->loadCount(['users', 'domains', 'patients', 'testOrders', 'reports', 'supportNotes']);
        $lab->load(['domains', 'users', 'supportNotes' => function($query) {
            $query->with('creator')->latest();
        }]);

        // Get website settings for this lab
        $settings = \App\Models\WebsiteSetting::where('lab_id', $lab->id)->first();
        
        // Get activity data
        $activityData = $this->getLabActivity($lab);

        return view('super-admin.labs.show', compact('lab', 'settings', 'activityData'));
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
        $settings = \App\Models\WebsiteSetting::firstOrCreate(
            ['lab_id' => $lab->id],
            [
                'brand_name' => $lab->name,
                'primary_color' => '#3B82F6',
            ]
        );

        $validated = $request->validate([
            'brand_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7',
        ]);

        $settings->update($validated);

        return redirect()->back()
            ->with('success', 'Lab settings updated successfully.');
    }

    /**
     * Update lab feature flags.
     */
    public function updateLabFeatures(Request $request, Lab $lab): RedirectResponse
    {
        $settings = \App\Models\WebsiteSetting::firstOrCreate(
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
            'email' => 'required|email|max:255|unique:labs,email,' . $lab->id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'level' => 'required|in:small,large',
            'status' => 'nullable|in:active,suspended',
        ]);

        $lab->update($validated);

        return redirect()->route('super-admin.labs.show', $lab)
            ->with('success', 'Lab updated successfully.');
    }

    /**
     * Delete a lab.
     */
    public function destroyLab(Lab $lab): RedirectResponse
    {
        $labName = $lab->name;
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

        return redirect()->back()
            ->with('success', 'Lab suspended successfully.');
    }

    /**
     * Activate a lab.
     */
    public function activateLab(Lab $lab): RedirectResponse
    {
        $lab->update(['status' => 'active']);

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

        \App\Models\Domain::create([
            'lab_id' => $lab->id,
            'domain' => $validated['domain'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->back()
            ->with('success', 'Domain added successfully.');
    }

    /**
     * Update a domain.
     */
    public function updateDomain(Request $request, Lab $lab, \App\Models\Domain $domain): RedirectResponse
    {
        if ($domain->lab_id !== $lab->id) {
            abort(404);
        }

        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:domains,domain,' . $domain->id,
            'is_active' => 'nullable|boolean',
        ]);

        $domain->update($validated);

        return redirect()->back()
            ->with('success', 'Domain updated successfully.');
    }

    /**
     * Delete a domain.
     */
    public function destroyDomain(Lab $lab, \App\Models\Domain $domain): RedirectResponse
    {
        if ($domain->lab_id !== $lab->id) {
            abort(404);
        }

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

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'lab_id' => $lab->id,
            'role' => $validated['role'],
        ]);

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
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

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
        ]);

        $lab->update($validated);

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

        \App\Models\LabSupportNote::create([
            'lab_id' => $lab->id,
            'created_by' => Auth::id(),
            'note' => $validated['note'],
            'type' => $validated['type'],
            'is_important' => $request->has('is_important') && $request->is_important == '1',
        ]);

        return redirect()->back()
            ->with('success', 'Support note added successfully.');
    }

    /**
     * Delete a support note.
     */
    public function destroySupportNote(Lab $lab, \App\Models\LabSupportNote $note): RedirectResponse
    {
        if ($note->lab_id !== $lab->id) {
            abort(404);
        }

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
            'users' => function($query) {
                $query->select('id', 'name', 'email', 'role', 'lab_id', 'created_at', 'updated_at');
            },
            'domains',
            'supportNotes' => function($query) {
                $query->with('creator:id,name');
            },
        ]);

        // Get counts without loading actual data
        $lab->loadCount(['patients', 'testOrders', 'reports']);

        $settings = \App\Models\WebsiteSetting::where('lab_id', $lab->id)->first();

        $filename = 'lab_' . $lab->id . '_export_' . date('Y-m-d_His') . '.json';
        
        // Prepare lab data excluding sensitive fields
        $labData = $lab->only([
            'id', 'name', 'email', 'phone', 'address', 'level', 'status',
            'subscription_plan', 'subscription_status', 'subscription_start_date',
            'subscription_end_date', 'monthly_fee', 'billing_notes',
            'created_at', 'updated_at'
        ]);
        
        $data = [
            'lab' => $labData,
            'settings' => $settings?->only([
                'lab_id', 'site_name', 'site_description', 'contact_email',
                'contact_phone', 'address', 'logo', 'favicon', 'primary_color',
                'secondary_color', 'created_at', 'updated_at'
            ]),
            'users' => $lab->users->map(function($user) {
                return $user->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']);
            }),
            'domains' => $lab->domains->toArray(),
            'support_notes' => $lab->supportNotes->map(function($note) {
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

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ], JSON_PRETTY_PRINT);
    }
}
