<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Inventory Dashboard</h2>
                <p class="text-sm text-gray-500 mt-1">Monitor stock levels and alerts</p>
            </div>
            <a href="{{ route('inventory.items.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                View All Items
            </a>
        </div>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Items" :value="$stats['total_items']" icon="package" color="indigo" />
        <x-stat-card title="Low Stock" :value="$stats['low_stock_count']" icon="exclamation-triangle" color="yellow" />
        <x-stat-card title="Out of Stock" :value="$stats['out_of_stock_count']" icon="x-circle" color="red" />
        <x-stat-card title="Stock Value" :value="'₹' . number_format($stats['total_value'], 2)" icon="currency-dollar" color="green" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Low Stock Items
                </h3>
            </div>
            <div class="p-6">
                @if($lowStockItems->count() > 0)
                    <div class="space-y-3">
                        @foreach($lowStockItems as $item)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        Current: {{ $item->current_stock }} {{ $item->unit }} | 
                                        Minimum: {{ $item->minimum_stock }} {{ $item->unit }}
                                    </p>
                                </div>
                                <a href="{{ route('inventory.items.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No low stock items. All items are well stocked!</p>
                @endif
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-red-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Expiring Soon (Next 30 Days)
                </h3>
            </div>
            <div class="p-6">
                @if($expiringItems->count() > 0)
                    <div class="space-y-3">
                        @foreach($expiringItems as $item)
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        Expires: {{ $item->expiry_date->format('M d, Y') }} 
                                        ({{ $item->expiry_date->diffForHumans() }})
                                    </p>
                                </div>
                                <a href="{{ route('inventory.items.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No items expiring soon.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Out of Stock & Expired Alerts -->
    @if($alerts['out_of_stock']->count() > 0 || $alerts['expired']->count() > 0)
    <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Critical Alerts
            </h3>
        </div>
        <div class="p-6">
            @if($alerts['out_of_stock']->count() > 0)
                <div class="mb-4">
                    <h4 class="font-semibold text-red-900 mb-2">Out of Stock Items</h4>
                    <div class="space-y-2">
                        @foreach($alerts['out_of_stock'] as $item)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                    <p class="text-sm text-red-600">Stock: 0 {{ $item->unit }}</p>
                                </div>
                                <a href="{{ route('inventory.items.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($alerts['expired']->count() > 0)
                <div>
                    <h4 class="font-semibold text-red-900 mb-2">Expired Items</h4>
                    <div class="space-y-2">
                        @foreach($alerts['expired'] as $item)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                    <p class="text-sm text-red-600">
                                        Expired: {{ $item->expiry_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <a href="{{ route('inventory.items.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
</x-app-layout>
