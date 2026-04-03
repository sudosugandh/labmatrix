<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Corporate accounts</h2>
                <p class="text-sm text-gray-500 mt-1">Employer logins for the corporate portal and health camps</p>
            </div>
            <a href="{{ route('corporate-accounts.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add account
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('corporate-accounts.index') }}" class="mb-6 flex gap-3">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, phone, email…"
            class="flex-1 max-w-md rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium">Search</button>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($accounts as $account)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $account->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $account->contact_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $account->phone ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($account->is_active)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('corporate-accounts.show', $account) }}" class="text-indigo-600 hover:text-indigo-800 font-medium mr-3">View</a>
                                <a href="{{ route('corporate-accounts.edit', $account) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">No corporate accounts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($accounts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $accounts->links() }}</div>
        @endif
    </div>
</x-app-layout>
