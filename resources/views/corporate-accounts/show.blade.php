<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $corporateAccount->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Corporate portal: phone + password · {{ url('/corporate-portal/login') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('corporate-accounts.edit', $corporateAccount) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">Edit</a>
                <a href="{{ route('corporate-accounts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg">Back</a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Details</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Contact</dt><dd class="font-medium text-gray-900">{{ $corporateAccount->contact_name ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Phone</dt><dd class="font-medium text-gray-900">{{ $corporateAccount->phone ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900">{{ $corporateAccount->email ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd>
                    @if($corporateAccount->is_active)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Inactive</span>
                    @endif
                </dd></div>
            </dl>
            @if($corporateAccount->notes)
                <p class="mt-4 text-sm text-gray-600 border-t border-gray-100 pt-4">{{ $corporateAccount->notes }}</p>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick actions</h3>
            <a href="{{ route('health-camps.create', ['corporate_account_id' => $corporateAccount->id]) }}" class="block w-full text-center px-4 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100 mb-2">New health camp</a>
            <p class="text-xs text-gray-500">Pass corporate account when creating a camp, or assign on the camp form.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-semibold text-gray-900">Recent health camps</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($corporateAccount->healthCamps as $camp)
                        <tr>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $camp->name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $camp->camp_date?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm capitalize">{{ $camp->status }}</td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('health-camps.show', $camp) }}" class="text-indigo-600 text-sm font-medium">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No camps yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        <form action="{{ route('corporate-accounts.destroy', $corporateAccount) }}" method="POST" onsubmit="return confirm('Delete this corporate account?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete account</button>
        </form>
    </div>
</x-app-layout>
