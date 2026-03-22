<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Cost Analysis</h2>
                <p class="text-sm text-gray-500 mt-1">Calculate cost per test and profitability</p>
            </div>
            <a href="{{ route('inventory.reports.index') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-semibold text-gray-900">Test Cost Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inventory Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Margin %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consumables</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($testCosts as $testCost)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $testCost['test']->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₹{{ number_format($testCost['test']->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ₹{{ number_format($testCost['total_cost'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $testCost['profit_margin'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                ₹{{ number_format($testCost['test']->price - $testCost['total_cost'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $testCost['profit_margin'] > 50 ? 'bg-green-100 text-green-800' : ($testCost['profit_margin'] > 20 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ number_format($testCost['profit_margin'], 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @foreach($testCost['consumables'] as $consumable)
                                    <div>{{ $consumable['quantity'] }}x {{ $consumable['item'] }} (₹{{ number_format($consumable['total_cost'], 2) }})</div>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No tests with consumables configured. 
                                <a href="{{ route('tests.index') }}" class="text-indigo-600 hover:text-indigo-900">Configure test consumables</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
