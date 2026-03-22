<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Stock Adjustments</h2>
                <p class="text-sm text-gray-500 mt-1">View all inventory stock adjustments</p>
            </div>
            <a href="{{ route('inventory.items.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Items</a>
        </div>
    </x-slot>

    <!-- Filters -->
    <x-search-filter :route="route('inventory.adjustments.index')">
        <x-slot name="filters">
            <select name="item_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Items</option>
                @foreach(\App\Models\InventoryItem::forCurrentLab()->orderBy('name')->get() as $item)
                    <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
            <select name="adjustment_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Types</option>
                <option value="add" {{ request('adjustment_type') == 'add' ? 'selected' : '' }}>Add</option>
                <option value="remove" {{ request('adjustment_type') == 'remove' ? 'selected' : '' }}>Remove</option>
                <option value="damage" {{ request('adjustment_type') == 'damage' ? 'selected' : '' }}>Damage</option>
                <option value="correction" {{ request('adjustment_type') == 'correction' ? 'selected' : '' }}>Correction</option>
            </select>
        </x-slot>
    </x-search-filter>

    <!-- Adjustments Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <x-data-table>
            <x-slot name="header">
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Note</th>
                    <th>By</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @forelse($adjustments as $adjustment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $adjustment->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $adjustment->item->name }}</div>
                            <div class="text-sm text-gray-500">{{ $adjustment->item->sku ?? '-' }}</div>
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
                            {{ $adjustment->quantity > 0 ? '+' : '' }}{{ $adjustment->quantity }} {{ $adjustment->item->unit }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $adjustment->note ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $adjustment->createdBy->name ?? 'System' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">No adjustments found.</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-data-table>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $adjustments->links() }}
        </div>
    </div>
</x-app-layout>
