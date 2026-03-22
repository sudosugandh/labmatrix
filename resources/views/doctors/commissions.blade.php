<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Commission Report</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $doctor->name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('doctors.show', $doctor) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
                    ← Back to Doctor
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <!-- Commission Summary -->
    <div class="mb-6 bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
            <h3 class="text-xl font-bold text-white">Commission Summary</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6">
                    <p class="text-sm text-gray-600 mb-2">Total Earned</p>
                    <p class="text-2xl font-bold text-green-900">₹{{ number_format($doctor->total_commission_earned, 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-6">
                    <p class="text-sm text-gray-600 mb-2">Total Paid</p>
                    <p class="text-2xl font-bold text-indigo-900">₹{{ number_format($doctor->total_commission_paid, 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl p-6">
                    <p class="text-sm text-gray-600 mb-2">Pending</p>
                    <p class="text-2xl font-bold text-orange-900">₹{{ number_format($doctor->pending_commission, 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl p-6">
                    <p class="text-sm text-gray-600 mb-2">Commission Rate</p>
                    <p class="text-2xl font-bold text-purple-900">
                        @if($doctor->commission_type === 'percentage')
                            {{ number_format($doctor->commission_rate, 2) }}%
                        @else
                            ₹{{ number_format($doctor->commission_rate, 2) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-2xl shadow-lg p-6">
        <form method="GET" action="{{ route('doctors.commissions', $doctor) }}" class="flex flex-wrap gap-4">
            <div class="min-w-[150px]">
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="From Date">
            </div>
            <div class="min-w-[150px]">
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="To Date">
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg">
                Filter
            </button>
            @if(request('status') || request('from_date') || request('to_date'))
                <a href="{{ route('doctors.commissions', $doctor) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Commissions Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Commission Records
            </h3>
        </div>
        <div class="p-6">
            @if($commissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Commission</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Payment Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paid By</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($commissions as $commission)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $commission->commission_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        ₹{{ number_format($commission->order_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                        ₹{{ number_format($commission->commission_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $commission->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($commission->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($commission->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $commission->payment_date ? $commission->payment_date->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $commission->paidBy ? $commission->paidBy->name : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($commission->status === 'pending')
                                            <button onclick="openPaymentModal({{ $commission->id }}, {{ $commission->commission_amount }})" 
                                                class="text-green-600 hover:text-green-900 font-semibold">
                                                Mark as Paid
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $commissions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No commissions found</h3>
                    <p class="mt-1 text-sm text-gray-500">Commissions will appear here when bills are paid.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Mark Commission as Paid</h3>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Optional notes about this payment"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closePaymentModal()" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Mark as Paid
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPaymentModal(commissionId, amount) {
            document.getElementById('paymentForm').action = `/doctor-commissions/${commissionId}/pay`;
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('paymentModal');
            if (event.target === modal) {
                closePaymentModal();
            }
        }
    </script>
</x-app-layout>
