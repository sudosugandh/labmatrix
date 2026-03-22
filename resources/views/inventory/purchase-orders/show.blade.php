<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Purchase Order: {{ $purchaseOrder->po_number }}</h2>
                <p class="text-sm text-gray-500 mt-1">View purchase order details</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($purchaseOrder->status === 'draft')
                    <a href="{{ route('inventory.purchase-orders.edit', $purchaseOrder) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        Edit
                    </a>
                @endif
                @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                    <button onclick="document.getElementById('receive-form').classList.toggle('hidden')" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                        Receive Stock
                    </button>
                @endif
                <a href="{{ route('inventory.purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- PO Information -->
        <x-detail-card title="Purchase Order Information" gradient="from-indigo-500 to-purple-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </x-slot>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-detail-field label="PO Number" :value="$purchaseOrder->po_number" />
                <x-detail-field label="Supplier" :value="$purchaseOrder->supplier->name" />
                <x-detail-field label="Status">
                    <x-status-badge :status="ucfirst($purchaseOrder->status)" :color="match($purchaseOrder->status) {
                        'received' => 'green',
                        'ordered' => 'blue',
                        'partial' => 'yellow',
                        'draft' => 'gray',
                        default => 'gray'
                    }" />
                </x-detail-field>
                <x-detail-field label="Order Date" :value="$purchaseOrder->order_date->format('M d, Y')" />
                <x-detail-field label="Expected Delivery" :value="$purchaseOrder->expected_delivery_date?->format('M d, Y') ?? 'N/A'" />
                <x-detail-field label="Received Date" :value="$purchaseOrder->received_date?->format('M d, Y') ?? 'N/A'" />
                <x-detail-field label="Created By" :value="$purchaseOrder->createdBy->name ?? 'N/A'" />
            </dl>
        </x-detail-card>

        <!-- Items -->
        <x-detail-card title="Items" gradient="from-blue-500 to-cyan-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Ordered</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($purchaseOrder->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->inventoryItem->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->inventoryItem->unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity_ordered }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity_received }}
                                    @if($item->quantity_ordered > $item->quantity_received)
                                        <span class="text-yellow-600">(Pending: {{ $item->quantity_ordered - $item->quantity_received }})</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item->unit_cost, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-detail-card>

        <!-- Totals -->
        <x-detail-card title="Financial Summary" gradient="from-green-500 to-emerald-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-detail-field label="Subtotal" :value="'₹' . number_format($purchaseOrder->subtotal, 2)" />
                <x-detail-field label="Tax Amount" :value="'₹' . number_format($purchaseOrder->tax_amount, 2)" />
                <x-detail-field label="Shipping Cost" :value="'₹' . number_format($purchaseOrder->shipping_cost, 2)" />
                <x-detail-field label="Discount" :value="'₹' . number_format($purchaseOrder->discount, 2)" />
                <x-detail-field label="Total Amount" :value="'₹' . number_format($purchaseOrder->total_amount, 2)" class="md:col-span-2 text-xl font-bold" />
            </dl>
        </x-detail-card>

        <!-- Receive Stock Form -->
        @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
        <div id="receive-form" class="hidden bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Receive Stock</h3>
            <form method="POST" action="{{ route('inventory.purchase-orders.receive', $purchaseOrder) }}">
                @csrf
                <div class="space-y-4">
                    @foreach($purchaseOrder->items as $item)
                        @if($item->quantity_ordered > $item->quantity_received)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $item->inventoryItem->name }}</h4>
                                        <p class="text-sm text-gray-500">Ordered: {{ $item->quantity_ordered }} | Received: {{ $item->quantity_received }} | Pending: {{ $item->quantity_ordered - $item->quantity_received }}</p>
                                    </div>
                                </div>
                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                <div class="mt-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity to Receive</label>
                                    <input type="number" name="items[{{ $loop->index }}][quantity_received]" 
                                        step="0.01" min="0" max="{{ $item->quantity_ordered - $item->quantity_received }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('receive-form').classList.add('hidden')" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <x-button-loading type="submit" class="bg-green-600 hover:bg-green-700">Receive Stock</x-button-loading>
                </div>
            </form>
        </div>
        @endif

        @if($purchaseOrder->notes)
        <x-detail-card title="Notes" gradient="from-gray-500 to-gray-600">
            <p class="text-gray-700">{{ $purchaseOrder->notes }}</p>
        </x-detail-card>
        @endif
    </div>
</x-app-layout>
