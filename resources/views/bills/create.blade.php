<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Create New Bill</h2>
                <p class="text-sm text-gray-500 mt-1">Select test orders and generate invoice</p>
            </div>
        </div>
    </x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <form method="POST" action="{{ route('bills.store') }}" id="billForm">
            @csrf

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-xl font-bold text-white">Bill Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                            <select name="patient_id" id="patient_id" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} - {{ $patient->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bill_date" class="block text-sm font-medium text-gray-700 mb-2">Bill Date *</label>
                            <input type="date" name="bill_date" id="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            @error('bill_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">Discount (₹)</label>
                            <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}" step="0.01" min="0"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            @error('discount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tax" class="block text-sm font-medium text-gray-700 mb-2">Tax (₹)</label>
                            <input type="number" name="tax" id="tax" value="{{ old('tax', 0) }}" step="0.01" min="0"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            @error('tax')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pending Test Orders -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
                    <h3 class="text-xl font-bold text-white">Select Test Orders</h3>
                </div>
                <div class="p-6">
                    @if($pendingOrders->count() > 0)
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Select All</span>
                            </label>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Select</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Test/Package</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Price (₹)</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($pendingOrders as $order)
                                        <tr>
                                            <td class="px-4 py-4">
                                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" 
                                                    class="order-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                    data-price="{{ $order->final_price > 0 ? $order->final_price : ($order->test ? $order->test->price : 0) }}">
                                            </td>
                                            <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $order->patient->name }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                @if($order->package)
                                                    <div>
                                                        <span class="font-semibold text-indigo-600">{{ $order->package->name }}</span>
                                                        <span class="text-xs text-gray-400 block">Package</span>
                                                        <span class="text-xs text-gray-600">{{ $order->test->name }}</span>
                                                    </div>
                                                @else
                                                    {{ $order->test->name }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">
                                                ₹{{ number_format($order->final_price > 0 ? $order->final_price : ($order->test ? $order->test->price : 0), 2) }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Selected Orders:</span>
                                <span id="selectedCount" class="text-sm font-semibold text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                                <span id="subtotal" class="text-lg font-bold text-indigo-600">₹0.00</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-4 text-gray-500 text-lg">No pending test orders found.</p>
                            <p class="mt-2 text-gray-400 text-sm">Create test orders first to generate bills.</p>
                        </div>
                    @endif
                </div>
            </div>

            @error('order_ids')
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <p>{{ $message }}</p>
                </div>
            @enderror

            <div class="flex justify-end gap-4">
                <a href="{{ route('bills.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200" 
                    {{ $pendingOrders->count() == 0 ? 'disabled' : '' }}>
                    Create Bill
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.order-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const subtotal = document.getElementById('subtotal');
    const discountInput = document.getElementById('discount');
    const taxInput = document.getElementById('tax');

    function updateTotals() {
        let count = 0;
        let total = 0;
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                count++;
                total += parseFloat(checkbox.dataset.price) || 0;
            }
        });

        selectedCount.textContent = count;
        subtotal.textContent = '₹' + total.toFixed(2);
    }

    selectAll?.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateTotals();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateTotals();
            if (selectAll) {
                selectAll.checked = Array.from(checkboxes).every(cb => cb.checked);
            }
        });
    });

    // Ensure at least one order is selected
    document.getElementById('billForm')?.addEventListener('submit', function(e) {
        const checked = Array.from(checkboxes).some(cb => cb.checked);
        if (!checked) {
            e.preventDefault();
            alert('Please select at least one test order.');
        }
    });
});
</script>
</x-app-layout>
