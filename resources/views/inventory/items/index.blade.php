<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Inventory Items</h2>
                <p class="text-sm text-gray-500 mt-1">Manage test kits, reagents, and supplies</p>
            </div>
            <a href="{{ route('inventory.items.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Item
            </a>
        </div>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Items" :value="$stats['total']" icon="package" color="indigo" />
        <x-stat-card title="Low Stock" :value="$stats['low_stock']" icon="exclamation-triangle" color="yellow" />
        <x-stat-card title="Out of Stock" :value="$stats['out_of_stock']" icon="x-circle" color="red" />
        <x-stat-card title="Total Value" :value="'₹' . number_format($stats['total_value'], 2)" icon="currency-dollar" color="green" />
    </div>

    <!-- Search and Filters -->
    <x-search-filter :route="route('inventory.items.index')">
        <x-slot name="search">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU..." 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </x-slot>
        <x-slot name="filters">
            <select name="stock_status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Stock Status</option>
                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                <option value="expiring" {{ request('stock_status') == 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
                <option value="expired" {{ request('stock_status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </x-slot>
    </x-search-filter>

    <!-- Items Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <x-data-table>
            <x-slot name="header">
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Stock</th>
                    <th>Unit</th>
                    <th>Cost</th>
                    <th>Expiry</th>
                    <th>Actions</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @forelse($items as $item)
                    <tr>
                        <td>
                            <div class="font-medium text-gray-900">{{ $item->name }}</div>
                        </td>
                        <td class="text-sm text-gray-500">{{ $item->sku ?? '-' }}</td>
                        <td>
                            <div class="flex items-center">
                                <span class="font-medium {{ $item->isLowStock() ? 'text-yellow-600' : ($item->isOutOfStock() ? 'text-red-600' : 'text-gray-900') }}">
                                    {{ $item->current_stock }}
                                </span>
                                @if($item->isLowStock())
                                    <svg class="w-4 h-4 text-yellow-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">Min: {{ $item->minimum_stock }}</div>
                        </td>
                        <td class="text-sm text-gray-500">{{ $item->unit }}</td>
                        <td class="text-sm text-gray-900">₹{{ number_format($item->cost, 2) }}</td>
                        <td class="text-sm text-gray-500">
                            @if($item->expiry_date)
                                <span class="{{ $item->isExpired() ? 'text-red-600' : ($item->isExpiringSoon() ? 'text-orange-600' : 'text-gray-500') }}">
                                    {{ $item->expiry_date->format('M d, Y') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('inventory.items.show', $item) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('inventory.items.edit', $item) }}" class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">No inventory items found.</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-data-table>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
    </div>
</x-app-layout>
