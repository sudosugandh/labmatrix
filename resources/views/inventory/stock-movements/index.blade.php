<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Stock Movements</h2>
                <p class="text-sm text-gray-500 mt-1">Track all inventory stock movements</p>
            </div>
            <a href="{{ route('inventory.items.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                ← Back to Inventory
            </a>
        </div>
    </x-slot>

    <x-search-filter :route="route('inventory.stock-movements.index')">
        <x-slot name="search">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by item name, reference..." 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </x-slot>
        <x-slot name="filters">
            <select name="type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Types</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Incoming</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Outgoing</option>
                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @if(request('item_id'))
                <input type="hidden" name="item_id" value="{{ request('item_id') }}">
            @endif
        </x-slot>
    </x-search-filter>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Before</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock After</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($movements as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->movement_date->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $movement->inventoryItem->name }}</div>
                                <div class="text-sm text-gray-500">{{ $movement->inventoryItem->unit }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge 
                                    :status="ucfirst($movement->type)" 
                                    :color="match($movement->type) {
                                        'in' => 'green',
                                        'out' => 'red',
                                        'adjustment' => 'blue',
                                        default => 'gray'
                                    }" 
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $movement->quantity }} {{ $movement->inventoryItem->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->stock_before }} {{ $movement->inventoryItem->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->stock_after }} {{ $movement->inventoryItem->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($movement->purchaseOrder)
                                    <a href="{{ route('inventory.purchase-orders.show', $movement->purchaseOrder) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $movement->reference_number ?? $movement->purchaseOrder->po_number }}
                                    </a>
                                @else
                                    {{ $movement->reference_number ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $movement->reason ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->createdBy->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="mt-4 text-lg font-medium">No stock movements found</p>
                                <p class="mt-2 text-sm">Stock movements will appear here when items are added, removed, or adjusted.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $movements->links() }}
        </div>
    </div>
</x-app-layout>
