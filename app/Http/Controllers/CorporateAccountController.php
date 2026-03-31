<?php

namespace App\Http\Controllers;

use App\Models\CorporateAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CorporateAccountController extends Controller
{
    public function index(Request $request): View
    {
        $query = CorporateAccount::where('lab_id', currentLabId())->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('contact_name', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $accounts = $query->paginate(15)->withQueryString();

        return view('corporate-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('corporate-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'portal_password' => 'nullable|string|min:8|confirmed',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        CorporateAccount::create([
            'lab_id' => currentLabId(),
            'name' => $validated['name'],
            'contact_name' => $validated['contact_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'password' => ! empty($validated['portal_password']) ? $validated['portal_password'] : null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('corporate-accounts.index')->with('success', 'Corporate account created.');
    }

    public function show(CorporateAccount $corporateAccount): View
    {
        $this->authorizeLab($corporateAccount);
        $corporateAccount->load(['healthCamps' => fn ($q) => $q->latest('camp_date')->take(20)]);

        return view('corporate-accounts.show', compact('corporateAccount'));
    }

    public function edit(CorporateAccount $corporateAccount): View
    {
        $this->authorizeLab($corporateAccount);

        return view('corporate-accounts.edit', compact('corporateAccount'));
    }

    public function update(Request $request, CorporateAccount $corporateAccount): RedirectResponse
    {
        $this->authorizeLab($corporateAccount);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'portal_password' => 'nullable|string|min:8|confirmed',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $corporateAccount->fill([
            'name' => $validated['name'],
            'contact_name' => $validated['contact_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (! empty($validated['portal_password'])) {
            $corporateAccount->password = $validated['portal_password'];
        }

        $corporateAccount->save();

        return redirect()->route('corporate-accounts.show', $corporateAccount)->with('success', 'Account updated.');
    }

    public function destroy(CorporateAccount $corporateAccount): RedirectResponse
    {
        $this->authorizeLab($corporateAccount);

        if ($corporateAccount->healthCamps()->exists()) {
            return back()->withErrors(['error' => 'Remove or reassign health camps first.']);
        }

        $corporateAccount->delete();

        return redirect()->route('corporate-accounts.index')->with('success', 'Corporate account deleted.');
    }

    private function authorizeLab(CorporateAccount $account): void
    {
        if ($account->lab_id !== currentLabId()) {
            abort(404);
        }
    }
}
