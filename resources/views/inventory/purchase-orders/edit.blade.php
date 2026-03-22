<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Purchase Order: {{ $purchaseOrder->po_number }}</h2>
                <p class="text-sm text-gray-500 mt-1">Update purchase order details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.purchase-orders.show', $purchaseOrder) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    View
                </a>
                <a href="{{ route('inventory.purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('inventory.purchase-orders.update', $purchaseOrder) }}" class="bg-white rounded-xl shadow-lg p-6" x-data="purchaseOrderForm()">
        @csrf
        @method('PUT')

        <!-- Supplier & Dates -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <x-form-select name="supplier_id" label="Supplier *" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </x-form-select>
            <x-form-input name="order_date" label="Order Date *" type="date" :value="old('order_date', $purchaseOrder->order_date->format('Y-m-d'))" required />
            <x-form-input name="expected_delivery_date" label="Expected Delivery Date" type="date" :value="old('expected_delivery_date', $purchaseOrder->expected_delivery_date?->format('Y-m-d'))" />
        </div>

        <!-- Items Section -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Items</h3>
                <button type="button" @click="addItem()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tax %</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Line Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-3">
                                    <select x-model="item.inventory_item_id" @change="updateItem(index)" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="">Select Item</option>
                                        @foreach($items as $invItem)
                                            <option value="{{ $invItem->id }}" 
                                                data-unit-cost="{{ $invItem->unit_cost }}"
                                                data-unit="{{ $invItem->unit }}"
                                                :selected="item.inventory_item_id == {{ $invItem->id }}">
                                                {{ $invItem->name }} ({{ $invItem->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" :name="`items[${index}][inventory_item_id]`" x-model="item.inventory_item_id">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" x-model="item.quantity_ordered" @input="calculateLineTotal(index)" step="0.01" min="0.01" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        :name="`items[${index}][quantity_ordered]`">
                                    <span class="text-xs text-gray-500" x-text="item.unit"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" x-model="item.unit_cost" @input="calculateLineTotal(index)" step="0.01" min="0" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        :name="`items[${index}][unit_cost]`">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" x-model="item.discount" @input="calculateLineTotal(index)" step="0.01" min="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        :name="`items[${index}][discount]`">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" x-model="item.tax_rate" @input="calculateLineTotal(index)" step="0.01" min="0" max="100"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        :name="`items[${index}][tax_rate]`">
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-gray-900" x-text="formatCurrency(item.line_total)"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No items added. Click "Add Item" to start.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                    <span class="text-sm font-bold text-gray-900" x-text="formatCurrency(totals.subtotal)"></span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Amount</label>
                    <input type="number" name="tax_amount" x-model="totals.tax_amount" @input="calculateTotal()" step="0.01" min="0"
                        value="{{ old('tax_amount', $purchaseOrder->tax_amount) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Cost</label>
                    <input type="number" name="shipping_cost" x-model="totals.shipping_cost" @input="calculateTotal()" step="0.01" min="0"
                        value="{{ old('shipping_cost', $purchaseOrder->shipping_cost) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount</label>
                    <input type="number" name="discount" x-model="totals.discount" @input="calculateTotal()" step="0.01" min="0"
                        value="{{ old('discount', $purchaseOrder->discount) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-200">
                    <span class="text-lg font-bold text-gray-900">Total:</span>
                    <span class="text-lg font-bold text-indigo-600" x-text="formatCurrency(totals.total)"></span>
                </div>
            </div>
            <div>
                <x-form-textarea name="notes" label="Notes" rows="5" :value="old('notes', $purchaseOrder->notes)" />
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('inventory.purchase-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <x-button-loading type="submit" class="bg-indigo-600 hover:bg-indigo-700">Update Purchase Order</x-button-loading>
        </div>
    </form>

    @push('scripts')
    <script>
        function purchaseOrderForm() {
            return {
                items: @json($purchaseOrder->items->map(function($item) {
                    return [
                        'inventory_item_id' => $item->inventory_item_id,
                        'quantity_ordered' => (float)$item->quantity_ordered,
                        'unit_cost' => (float)$item->unit_cost,
                        'discount' => (float)($item->discount ?? 0),
                        'tax_rate' => (float)($item->tax_rate ?? 0),
                        'line_total' => (float)$item->line_total,
                        'unit' => $item->inventoryItem->unit ?? ''
                    ];
                })),
                totals: {
                    subtotal: {{ $purchaseOrder->subtotal }},
                    tax_amount: {{ $purchaseOrder->tax_amount }},
                    shipping_cost: {{ $purchaseOrder->shipping_cost }},
                    discount: {{ $purchaseOrder->discount }},
                    total: {{ $purchaseOrder->total_amount }}
                },
                
                init() {
                    if (this.items.length === 0) {
                        this.addItem();
                    }
                },
                
                addItem() {
                    this.items.push({
                        inventory_item_id: '',
                        quantity_ordered: 1,
                        unit_cost: 0,
                        discount: 0,
                        tax_rate: 0,
                        line_total: 0,
                        unit: ''
                    });
                },
                
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateSubtotal();
                },
                
                updateItem(index) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    if (option.dataset.unitCost) {
                        this.items[index].unit_cost = parseFloat(option.dataset.unitCost) || 0;
                        this.items[index].unit = option.dataset.unit || '';
                        this.calculateLineTotal(index);
                    }
                },
                
                calculateLineTotal(index) {
                    const item = this.items[index];
                    const subtotal = (item.quantity_ordered * item.unit_cost) - (item.discount || 0);
                    const tax = subtotal * ((item.tax_rate || 0) / 100);
                    item.line_total = subtotal + tax;
                    this.calculateSubtotal();
                },
                
                calculateSubtotal() {
                    this.totals.subtotal = this.items.reduce((sum, item) => sum + (item.line_total || 0), 0);
                    this.calculateTotal();
                },
                
                calculateTotal() {
                    this.totals.total = this.totals.subtotal + 
                                       (parseFloat(this.totals.tax_amount) || 0) + 
                                       (parseFloat(this.totals.shipping_cost) || 0) - 
                                       (parseFloat(this.totals.discount) || 0);
                },
                
                formatCurrency(value) {
                    return '₹' + (parseFloat(value) || 0).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
