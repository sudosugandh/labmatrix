<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Usage Report</h2>
                <p class="text-sm text-gray-500 mt-1">Most consumed items and consumption rates</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('inventory.reports.usage') }}" class="flex items-center space-x-2">
                    <select name="period" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                        <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 Days</option>
                    </select>
                </form>
                <a href="{{ route('inventory.reports.export', ['type' => 'usage', 'period' => $period]) }}" 
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
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-semibold text-gray-900">Item Consumption (Last {{ $period }} Days)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Consumed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consumption Rate/Day</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Until Out</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usage as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $item->item->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->item->sku ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($item->total_consumed, 2) }} {{ $item->item->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($item->consumption_rate, 2) }} {{ $item->item->unit }}/day
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($item->item->current_stock, 2) }} {{ $item->item->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->days_until_out > 0 && $item->days_until_out < 30)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $item->days_until_out }} days
                                    </span>
                                @elseif($item->days_until_out <= 0)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        {{ $item->days_until_out }} days
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">No consumption data available for the selected period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
