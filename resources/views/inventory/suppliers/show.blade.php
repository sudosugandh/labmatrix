<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $supplier->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Supplier Details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.suppliers.edit', $supplier) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    Edit
                </a>
                <a href="{{ route('inventory.purchase-orders.create', ['supplier_id' => $supplier->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                    Create PO
                </a>
                <a href="{{ route('inventory.suppliers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Supplier Information -->
        <x-detail-card title="Supplier Information" gradient="from-indigo-500 to-purple-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </x-slot>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-detail-field label="Contact Person" :value="$supplier->contact_person ?? 'N/A'" />
                <x-detail-field label="Email" :value="$supplier->email ?? 'N/A'" />
                <x-detail-field label="Phone" :value="$supplier->phone ?? 'N/A'" />
                <x-detail-field label="Alternate Phone" :value="$supplier->alternate_phone ?? 'N/A'" />
                <x-detail-field label="Address" :value="$supplier->address ?? 'N/A'" class="md:col-span-2" />
                <x-detail-field label="City" :value="$supplier->city ?? 'N/A'" />
                <x-detail-field label="State" :value="$supplier->state ?? 'N/A'" />
                <x-detail-field label="Zip Code" :value="$supplier->zip_code ?? 'N/A'" />
                <x-detail-field label="Country" :value="$supplier->country ?? 'N/A'" />
                <x-detail-field label="Tax ID" :value="$supplier->tax_id ?? 'N/A'" />
                <x-detail-field label="Payment Terms" :value="$supplier->payment_terms ?? 'N/A'" />
                <x-detail-field label="Status">
                    <x-status-badge :status="$supplier->is_active ? 'Active' : 'Inactive'" :color="$supplier->is_active ? 'green' : 'gray'" />
                </x-detail-field>
                @if($supplier->notes)
                    <x-detail-field label="Notes" :value="$supplier->notes" class="md:col-span-2" />
                @endif
            </dl>
        </x-detail-card>

        <!-- Statistics -->
        <x-detail-card title="Statistics" gradient="from-blue-500 to-cyan-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </x-slot>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-detail-field label="Total Purchase Orders" :value="$supplier->purchase_orders_count" />
                <x-detail-field label="Preferred Items" :value="$supplier->inventory_items_count" />
            </dl>
        </x-detail-card>

        <!-- Recent Purchase Orders -->
        @if($supplier->purchaseOrders->count() > 0)
        <x-detail-card title="Recent Purchase Orders" gradient="from-green-500 to-emerald-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PO Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($supplier->purchaseOrders as $po)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $po->po_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $po->order_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format($po->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="ucfirst($po->status)" :color="match($po->status) {
                                        'received' => 'green',
                                        'ordered' => 'blue',
                                        'partial' => 'yellow',
                                        default => 'gray'
                                    }" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('inventory.purchase-orders.show', $po) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
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
