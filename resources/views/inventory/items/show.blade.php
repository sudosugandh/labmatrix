<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $item->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Inventory Item Details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.items.edit', $item) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    Edit
                </a>
                <a href="{{ route('inventory.items.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Stock Information Card -->
        <x-detail-card title="Stock Information" gradient="from-blue-500 to-cyan-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </x-slot>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-detail-field label="Current Stock" :value="$item->current_stock . ' ' . $item->unit" />
                <x-detail-field label="Minimum Stock" :value="$item->minimum_stock . ' ' . $item->unit" />
                <x-detail-field label="Stock Status">
                    @if($item->isOutOfStock())
                        <x-status-badge status="Out of Stock" color="red" />
                    @elseif($item->isLowStock())
                        <x-status-badge status="Low Stock" color="yellow" />
                    @else
                        <x-status-badge status="In Stock" color="green" />
                    @endif
                </x-detail-field>
                <x-detail-field label="Stock Value" :value="'₹' . number_format($item->current_stock * $item->cost, 2)" />
                <x-detail-field label="Cost per Unit" :value="'₹' . number_format($item->cost, 2)" />
                @if($item->expiry_date)
                    <x-detail-field label="Expiry Date" :value="$item->expiry_date->format('M d, Y')">
                        @if($item->isExpired())
                            <x-status-badge status="Expired" color="red" />
                        @elseif($item->isExpiringSoon())
                            <x-status-badge status="Expiring Soon" color="orange" />
                        @endif
                    </x-detail-field>
                @endif
            </dl>
        </x-detail-card>

        <!-- Item Details Card -->
        <x-detail-card title="Item Details" gradient="from-indigo-500 to-purple-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-detail-field label="SKU" :value="$item->sku ?? 'N/A'" />
                <x-detail-field label="Unit" :value="$item->unit" />
            </dl>
        </x-detail-card>

        <!-- Stock Adjustment Card -->
        <x-detail-card title="Stock Adjustment" gradient="from-green-500 to-emerald-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </x-slot>
            <form action="{{ route('inventory.items.adjust-stock', $item) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Type *</label>
                        <select name="adjustment_type" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="add">Add Stock</option>
                            <option value="remove">Remove Stock</option>
                            <option value="damage">Damage/Loss</option>
                            <option value="correction">Correction</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="quantity" step="0.01" min="0.01" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <textarea name="note" rows="3" placeholder="Reason for adjustment..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="flex justify-end">
                    <x-button-loading type="submit" class="bg-indigo-600 hover:bg-indigo-700">Apply Adjustment</x-button-loading>
                </div>
            </form>
        </x-detail-card>

        <!-- Adjustment History -->
        @if($item->adjustments->count() > 0)
        <x-detail-card title="Adjustment History" gradient="from-purple-500 to-pink-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($item->adjustments->take(20) as $adjustment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $adjustment->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge 
                                        :status="ucfirst($adjustment->adjustment_type)" 
                                        :color="match($adjustment->adjustment_type) {
                                            'add' => 'green',
                                            'remove' => 'red',
                                            'damage' => 'red',
                                            'correction' => 'blue',
                                            default => 'gray'
                                        }" 
                                    />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $adjustment->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $adjustment->quantity > 0 ? '+' : '' }}{{ $adjustment->quantity }} {{ $item->unit }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $adjustment->note ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $adjustment->createdBy->name ?? 'System' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-detail-card>
        @endif
    </div>
</x-app-layout>
