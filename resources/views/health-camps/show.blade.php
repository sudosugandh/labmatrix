<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $healthCamp->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if($healthCamp->corporateAccount)
                        {{ $healthCamp->corporateAccount->name }} ·
                    @endif
                    {{ $healthCamp->camp_date?->format('M j, Y') ?? 'No date' }}
                    · <span class="capitalize">{{ $healthCamp->status }}</span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('health-camps.edit', $healthCamp) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">Edit</a>
                <a href="{{ route('health-camps.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg">Back</a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Venue & notes</h3>
            <p class="text-sm text-gray-600">{{ $healthCamp->venue ?? '—' }}</p>
            @if($healthCamp->notes)
                <p class="mt-4 text-sm text-gray-700 border-t border-gray-100 pt-4">{{ $healthCamp->notes }}</p>
            @endif
        </div>
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Link test order</h3>
            <p class="text-sm text-gray-600 mb-4">Enter a test order ID from this lab to attach it to this camp (e.g. from Test Orders list).</p>
            <form method="POST" action="{{ route('health-camps.attach-order', $healthCamp) }}" class="flex flex-wrap gap-3 items-end">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label for="test_order_id" class="block text-sm font-medium text-gray-700 mb-1">Test order ID</label>
                    <input type="number" name="test_order_id" id="test_order_id" min="1" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. 1042">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg text-sm">Attach order</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50">
            <h3 class="text-lg font-semibold text-gray-900">Orders in this camp</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Test</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-sm font-medium text-indigo-600">
                                <a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $order->patient?->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $order->test?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-sm">No orders linked to this camp yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $orders->links() }}</div>
        @endif
    </div>

    <div class="mt-8">
        <form action="{{ route('health-camps.destroy', $healthCamp) }}" method="POST" onsubmit="return confirm('Delete this camp? Orders will be unlinked first only if empty — remove orders from camp or delete blocked.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete camp</button>
        </form>
    </div>
</x-app-layout>
