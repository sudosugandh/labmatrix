<?php

namespace App\Http\Controllers;

use App\Models\CorporateAccount;
use App\Models\HealthCamp;
use App\Models\TestOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HealthCampController extends Controller
{
    public function index(Request $request): View
    {
        $query = HealthCamp::where('lab_id', currentLabId())
            ->with('corporateAccount')
            ->withCount('testOrders')
            ->latest('camp_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $camps = $query->paginate(15)->withQueryString();

        return view('health-camps.index', compact('camps'));
    }

    public function create(Request $request): View
    {
        $corporates = CorporateAccount::where('lab_id', currentLabId())->where('is_active', true)->orderBy('name')->get();
        $corporateAccountId = $request->query('corporate_account_id');

        return view('health-camps.create', compact('corporates', 'corporateAccountId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'corporate_account_id' => 'nullable|exists:corporate_accounts,id',
            'name' => 'required|string|max:255',
            'camp_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if (! empty($validated['corporate_account_id'])) {
            $corp = CorporateAccount::where('id', $validated['corporate_account_id'])->where('lab_id', currentLabId())->first();
            if (! $corp) {
                return back()->withErrors(['corporate_account_id' => 'Invalid corporate account.'])->withInput();
            }
        }

        HealthCamp::create([
            'lab_id' => currentLabId(),
            'corporate_account_id' => $validated['corporate_account_id'] ?? null,
            'name' => $validated['name'],
            'camp_date' => $validated['camp_date'] ?? null,
            'venue' => $validated['venue'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('health-camps.index')->with('success', 'Health camp created.');
    }

    public function show(HealthCamp $healthCamp): View
    {
        $this->authorizeLab($healthCamp);
        $healthCamp->load('corporateAccount');
        $orders = TestOrder::where('lab_id', currentLabId())
            ->where('health_camp_id', $healthCamp->id)
            ->with(['patient', 'test'])
            ->latest()
            ->paginate(30);

        return view('health-camps.show', compact('healthCamp', 'orders'));
    }

    public function attachOrder(Request $request, HealthCamp $healthCamp): RedirectResponse
    {
        $this->authorizeLab($healthCamp);

        $validated = $request->validate([
            'test_order_id' => 'required|exists:test_orders,id',
        ]);

        $order = TestOrder::where('lab_id', currentLabId())
            ->whereKey($validated['test_order_id'])
            ->firstOrFail();

        $order->update(['health_camp_id' => $healthCamp->id]);

        return back()->with('success', 'Order linked to this camp.');
    }

    public function edit(HealthCamp $healthCamp): View
    {
        $this->authorizeLab($healthCamp);
        $corporates = CorporateAccount::where('lab_id', currentLabId())->where('is_active', true)->orderBy('name')->get();

        return view('health-camps.edit', compact('healthCamp', 'corporates'));
    }

    public function update(Request $request, HealthCamp $healthCamp): RedirectResponse
    {
        $this->authorizeLab($healthCamp);

        $validated = $request->validate([
            'corporate_account_id' => 'nullable|exists:corporate_accounts,id',
            'name' => 'required|string|max:255',
            'camp_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if (! empty($validated['corporate_account_id'])) {
            $corp = CorporateAccount::where('id', $validated['corporate_account_id'])->where('lab_id', currentLabId())->first();
            if (! $corp) {
                return back()->withErrors(['corporate_account_id' => 'Invalid corporate account.'])->withInput();
            }
        }

        $healthCamp->update([
            'corporate_account_id' => $validated['corporate_account_id'] ?? null,
            'name' => $validated['name'],
            'camp_date' => $validated['camp_date'] ?? null,
            'venue' => $validated['venue'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('health-camps.show', $healthCamp)->with('success', 'Camp updated.');
    }

    public function destroy(HealthCamp $healthCamp): RedirectResponse
    {
        $this->authorizeLab($healthCamp);

        if ($healthCamp->testOrders()->exists()) {
            return back()->withErrors(['error' => 'This camp has linked test orders. Remove camp from orders first.']);
        }

        $healthCamp->delete();

        return redirect()->route('health-camps.index')->with('success', 'Health camp deleted.');
    }

    private function authorizeLab(HealthCamp $camp): void
    {
        if ($camp->lab_id !== currentLabId()) {
            abort(404);
        }
    }
}
