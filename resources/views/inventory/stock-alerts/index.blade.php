<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Stock Alerts</h2>
                <p class="text-sm text-gray-500 mt-1">Monitor low stock, out of stock, and expiry alerts</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <x-stat-card title="Total Alerts" :value="$stats['total']" icon="exclamation-triangle" color="red" />
        <x-stat-card title="Low Stock" :value="$stats['low_stock']" icon="arrow-down" color="yellow" />
        <x-stat-card title="Out of Stock" :value="$stats['out_of_stock']" icon="x-circle" color="red" />
        <x-stat-card title="Expiring Soon" :value="$stats['expiring_soon']" icon="clock" color="orange" />
        <x-stat-card title="Expired" :value="$stats['expired']" icon="ban" color="red" />
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <x-data-table>
            <x-slot name="header">
                <tr>
                    <th>Item</th>
                    <th>Alert Type</th>
                    <th>Current Stock</th>
                    <th>Threshold/Expiry</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @forelse($alerts as $alert)
                    <tr class="{{ $alert->alert_type === 'out_of_stock' || $alert->alert_type === 'expired' ? 'bg-red-50' : '' }}">
                        <td class="font-medium text-gray-900">{{ $alert->inventoryItem->name }}</td>
                        <td>
                            <x-status-badge 
                                :status="ucfirst(str_replace('_', ' ', $alert->alert_type))" 
                                :color="match($alert->alert_type) {
                                    'out_of_stock' => 'red',
                                    'expired' => 'red',
                                    'low_stock' => 'yellow',
                                    'expiring_soon' => 'orange',
                                    default => 'gray'
                                }" 
                            />
                        </td>
                        <td class="text-sm text-gray-900">{{ $alert->current_stock }} {{ $alert->inventoryItem->unit }}</td>
                        <td class="text-sm text-gray-500">
                            @if($alert->threshold)
                                Min: {{ $alert->threshold }} {{ $alert->inventoryItem->unit }}
                            @elseif($alert->expiry_date)
                                {{ $alert->expiry_date->format('M d, Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-sm text-gray-500">{{ $alert->created_at->diffForHumans() }}</td>
                        <td>
                            <form action="{{ route('inventory.stock-alerts.resolve', $alert) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('inventory.items.show', $alert->inventoryItem) }}" class="ml-2 text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">No stock alerts found. All items are well stocked!</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-data-table>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $alerts->links() }}
        </div>
    </div>
</x-app-layout>
