<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Bill Details</h2>
                <p class="text-sm text-gray-500 mt-1">Bill #{{ $bill->bill_number }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($bill->due_amount > 0)
                <button type="button" id="copy-payment-link" data-url="{{ $bill->getPaymentLinkUrl() }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.172-1.172a4 4 0 005.656-5.656l-4-4z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path></svg>
                    Copy payment link
                </button>
                @endif
                <a href="{{ route('bills.print', $bill) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Print Invoice
                </a>
                <a href="{{ route('bills.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Back to Bills
                </a>
            </div>
        </div>
    </x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Bill Information -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-xl font-bold text-white">Bill Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Bill Number</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $bill->bill_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Bill Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $bill->bill_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Patient</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $bill->patient->name }}</p>
                        <p class="text-sm text-gray-500">{{ $bill->patient->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status</p>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $bill->status === 'paid' ? 'bg-green-100 text-green-800' :
                               ($bill->status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                               ($bill->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($bill->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Orders -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600">
                <h3 class="text-xl font-bold text-white">Test Orders ({{ $bill->testOrders->count() }})</h3>
            </div>
            <div class="p-6">
                @if($bill->testOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Test Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Price (₹)</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($bill->testOrders as $order)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            @if($order->package)
                                                <div>
                                                    <span class="font-semibold text-indigo-600">{{ $order->package->name }}</span>
                                                    <span class="text-xs text-gray-400 ml-2">(Package)</span>
                                                    <div class="text-xs text-gray-600 mt-1">{{ $order->test->name }}</div>
                                                </div>
                                            @else
                                                {{ $order->test->name }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">₹{{ number_format($order->final_price > 0 ? $order->final_price : $order->test->price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
                    <h3 class="text-xl font-bold text-white">Payment Summary</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">₹{{ number_format($bill->subtotal, 2) }}</span>
                        </div>
                        @if($bill->discount > 0)
                        <div class="flex justify-between text-red-600">
                            <span>Discount:</span>
                            <span class="font-semibold">-₹{{ number_format($bill->discount, 2) }}</span>
                        </div>
                        @endif
                        @if($bill->tax > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax:</span>
                            <span class="font-semibold">₹{{ number_format($bill->tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="border-t pt-4 flex justify-between">
                            <span class="text-lg font-bold text-gray-900">Total:</span>
                            <span class="text-lg font-bold text-indigo-600">₹{{ number_format($bill->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Paid:</span>
                            <span class="font-semibold">₹{{ number_format($bill->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-red-600">
                            <span>Due:</span>
                            <span class="font-semibold">₹{{ number_format($bill->due_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Payment Form -->
            @if($bill->due_amount > 0)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600">
                    <h3 class="text-xl font-bold text-white">Add Payment</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('payments.store', $bill) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (₹) *</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0.01" max="{{ $bill->due_amount }}" 
                                    value="{{ $bill->due_amount }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                <p class="mt-1 text-xs text-gray-500">Maximum: ₹{{ number_format($bill->due_amount, 2) }}</p>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                <select name="payment_method" id="payment_method" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="online">Online</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="transactionField" style="display: none;">
                                <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                                <input type="text" name="transaction_id" id="transaction_id"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            </div>

                            <div id="chequeField" style="display: none;">
                                <label for="cheque_number" class="block text-sm font-medium text-gray-700 mb-2">Cheque Number *</label>
                                <input type="text" name="cheque_number" id="cheque_number"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                                <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                @error('payment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" id="notes" rows="2"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Payment History -->
        @if($bill->payments->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600">
                <h3 class="text-xl font-bold text-white">Payment History</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Received By</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($bill->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">₹{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->receivedBy->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <form method="POST" action="{{ route('payments.destroy', $payment) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.getElementById('copy-payment-link')?.addEventListener('click', function() {
    var url = this.getAttribute('data-url');
    if (!url) return;
    navigator.clipboard.writeText(url).then(function() {
        var btn = document.getElementById('copy-payment-link');
        var orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied!';
        btn.classList.add('bg-green-700');
        setTimeout(function() { btn.innerHTML = orig; btn.classList.remove('bg-green-700'); }, 2000);
    });
});
document.getElementById('payment_method')?.addEventListener('change', function() {
    const transactionField = document.getElementById('transactionField');
    const chequeField = document.getElementById('chequeField');
    const chequeNumber = document.getElementById('cheque_number');
    
    if (this.value === 'online' || this.value === 'card' || this.value === 'bank_transfer') {
        transactionField.style.display = 'block';
        chequeField.style.display = 'none';
        if (chequeNumber) chequeNumber.removeAttribute('required');
    } else if (this.value === 'cheque') {
        transactionField.style.display = 'none';
        chequeField.style.display = 'block';
        if (chequeNumber) chequeNumber.setAttribute('required', 'required');
    } else {
        transactionField.style.display = 'none';
        chequeField.style.display = 'none';
        if (chequeNumber) chequeNumber.removeAttribute('required');
    }
});
</script>
</x-app-layout>
