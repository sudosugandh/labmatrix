<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Health camps</h2>
                <p class="text-sm text-gray-500 mt-1">Corporate camps, bulk bookings, and camp-wise reporting</p>
            </div>
            <a href="{{ route('health-camps.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add camp
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-6 flex gap-3 items-end">
        <div>
            <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" id="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 shadow-sm text-sm">
                <option value="">All</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Camp</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Corporate</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Orders</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($camps as $camp)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $camp->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $camp->corporateAccount?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $camp->camp_date?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm capitalize">{{ $camp->status }}</td>
                            <td class="px-6 py-4 text-sm text-right text-gray-700">{{ $camp->test_orders_count }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('health-camps.show', $camp) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No health camps yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($camps->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $camps->links() }}</div>
        @endif
    </div>
</x-app-layout>
