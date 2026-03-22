<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Reorder Recommendations</h2>
                <p class="text-sm text-gray-500 mt-1">Smart recommendations based on consumption patterns</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.reports.export', ['type' => 'reorder']) }}" 
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
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-semibold text-gray-900">Items Requiring Reorder</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Minimum Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daily Consumption</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Until Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recommended Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estimated Cost</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recommendations as $rec)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($rec['priority'] === 'critical')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Critical</span>
                                @elseif($rec['priority'] === 'high')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">High</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $rec['item']->name }}</div>
                                <div class="text-sm text-gray-500">{{ $rec['item']->sku ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $rec['current_stock'] <= 0 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ number_format($rec['current_stock'], 2) }} {{ $rec['item']->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($rec['minimum_stock'], 2) }} {{ $rec['item']->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($rec['daily_consumption'], 2) }} {{ $rec['item']->unit }}/day
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $rec['days_until_out'] < 7 ? 'text-red-600' : ($rec['days_until_out'] < 30 ? 'text-orange-600' : 'text-gray-900') }}">
                                {{ $rec['days_until_out'] }} days
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                {{ number_format($rec['recommended_quantity'], 0) }} {{ $rec['item']->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₹{{ number_format($rec['estimated_cost'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                All items are well stocked! No reorder recommendations at this time.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($recommendations->count() > 0)
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h4 class="font-semibold text-blue-900 mb-2">💡 How Recommendations Work</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Recommendations are based on average daily consumption over the last 30 days</li>
            <li>• Recommended quantity ensures stock for approximately 60 days</li>
            <li>• Priority is determined by current stock level and days until out of stock</li>
            <li>• Critical: Out of stock or will run out in less than 7 days</li>
        </ul>
    </div>
    @endif
</x-app-layout>
