<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Expiry Tracking</h2>
                <p class="text-sm text-gray-500 mt-1">Monitor items expiring within {{ $days }} days</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('inventory.reports.expiry') }}" class="flex items-center space-x-2">
                    <select name="days" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                        <option value="30" {{ $days == 30 ? 'selected' : '' }}>Next 30 Days</option>
                        <option value="60" {{ $days == 60 ? 'selected' : '' }}>Next 60 Days</option>
                        <option value="90" {{ $days == 90 ? 'selected' : '' }}>Next 90 Days</option>
                    </select>
                </form>
                <a href="{{ route('inventory.reports.export', ['type' => 'expiry', 'days' => $days]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('inventory.reports.index') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
            <h3 class="text-lg font-semibold text-gray-900">Items Expiring Soon</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Until Expiry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item['status'] === 'expired')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Expired</span>
                                @elseif($item['status'] === 'critical')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Critical</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Warning</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $item['item']->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['item']->sku ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($item['item']->current_stock, 2) }} {{ $item['item']->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $item['status'] === 'expired' ? 'text-red-600' : ($item['status'] === 'critical' ? 'text-orange-600' : 'text-gray-900') }}">
                                {{ $item['expiry_date']->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $item['days_until_expiry'] < 0 ? 'text-red-600' : ($item['days_until_expiry'] <= 7 ? 'text-orange-600' : 'text-gray-900') }}">
                                @if($item['days_until_expiry'] < 0)
                                    Expired {{ abs($item['days_until_expiry']) }} days ago
                                @else
                                    {{ $item['days_until_expiry'] }} days
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₹{{ number_format($item['stock_value'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No items expiring within {{ $days }} days. Great job managing inventory!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
