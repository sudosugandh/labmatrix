<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Purchase Orders</h2>
                <p class="text-sm text-gray-500 mt-1">Manage purchase orders and stock receipts</p>
            </div>
            <a href="{{ route('inventory.purchase-orders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Purchase Order
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <x-stat-card title="Total POs" :value="$stats['total']" icon="document-text" color="indigo" />
        <x-stat-card title="Pending" :value="$stats['pending']" icon="clock" color="yellow" />
        <x-stat-card title="Ordered" :value="$stats['ordered']" icon="check-circle" color="blue" />
        <x-stat-card title="Received" :value="$stats['received']" icon="check" color="green" />
        <x-stat-card title="Total Value" :value="'₹' . number_format($stats['total_value'], 2)" icon="currency-dollar" color="purple" />
    </div>

    <x-search-filter :route="route('inventory.purchase-orders.index')">
        <x-slot name="search">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by PO number, supplier..." 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </x-slot>
        <x-slot name="filters">
            <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
            </select>
            <select name="supplier_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </x-slot>
    </x-search-filter>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <x-data-table>
            <x-slot name="header">
                <tr>
                    <th>PO Number</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Expected Delivery</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @forelse($orders as $order)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $order->po_number }}</td>
                        <td>{{ $order->supplier->name }}</td>
                        <td class="text-sm text-gray-500">{{ $order->order_date->format('M d, Y') }}</td>
                        <td class="text-sm text-gray-500">{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('M d, Y') : '-' }}</td>
                        <td class="font-medium text-gray-900">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <x-status-badge :status="ucfirst($order->status)" :color="match($order->status) {
                                'received' => 'green',
                                'ordered' => 'blue',
                                'partial' => 'yellow',
                                'pending' => 'yellow',
                                'cancelled' => 'red',
                                default => 'gray'
                            }" />
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('inventory.purchase-orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">No purchase orders found.</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-data-table>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
