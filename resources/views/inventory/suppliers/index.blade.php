<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Suppliers</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your suppliers and vendors</p>
            </div>
            <a href="{{ route('inventory.suppliers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Supplier
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat-card title="Total Suppliers" :value="$stats['total']" icon="users" color="indigo" />
        <x-stat-card title="Active" :value="$stats['active']" icon="check-circle" color="green" />
        <x-stat-card title="Inactive" :value="$stats['inactive']" icon="x-circle" color="gray" />
    </div>

    <x-search-filter :route="route('inventory.suppliers.index')">
        <x-slot name="search">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, contact, email..." 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </x-slot>
        <x-slot name="filters">
            <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </x-slot>
    </x-search-filter>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <x-data-table>
            <x-slot name="header">
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Purchase Orders</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @forelse($suppliers as $supplier)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $supplier->name }}</td>
                        <td class="text-sm text-gray-500">{{ $supplier->contact_person ?? '-' }}</td>
                        <td class="text-sm text-gray-500">{{ $supplier->email ?? '-' }}</td>
                        <td class="text-sm text-gray-500">{{ $supplier->phone ?? '-' }}</td>
                        <td class="text-sm text-gray-500">{{ $supplier->purchase_orders_count }}</td>
                        <td>
                            <x-status-badge :status="$supplier->is_active ? 'Active' : 'Inactive'" :color="$supplier->is_active ? 'green' : 'gray'" />
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('inventory.suppliers.show', $supplier) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('inventory.suppliers.edit', $supplier) }}" class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">No suppliers found.</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-data-table>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $suppliers->links() }}
        </div>
    </div>
</x-app-layout>
