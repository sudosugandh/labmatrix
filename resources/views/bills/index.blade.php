<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Bills & Invoices</h2>
                <p class="text-sm text-slate-500 mt-1">Manage patient bills and payments</p>
            </div>
            <a href="{{ route('bills.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 shadow-lg shadow-violet-500/25 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Bill
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800" role="alert">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <a href="{{ route('bills.index') }}" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-blue-200 transition-all duration-200 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Bills</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stats['total_bills']) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                </div>
            </div>
        </a>
        <a href="{{ route('bills.index') }}?status=pending" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-red-200 transition-all duration-200 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending Amount</p>
                    <p class="text-2xl font-bold text-red-600 mt-2">₹{{ number_format($stats['pending_amount'], 0) }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </a>
        <a href="{{ route('bills.index') }}?status=paid" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-green-200 transition-all duration-200 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Paid</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">₹{{ number_format($stats['paid_amount'], 0) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </a>
        <a href="{{ route('bills.index') }}?date_from={{ now()->format('Y-m-d') }}&date_to={{ now()->format('Y-m-d') }}" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-violet-200 transition-all duration-200 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Today's Revenue</p>
                    <p class="text-2xl font-bold text-violet-600 mt-2">₹{{ number_format($stats['today_revenue'], 0) }}</p>
                </div>
                <div class="p-3 bg-violet-100 rounded-lg">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </a>
        <a href="{{ route('bills.index') }}?date_from={{ now()->startOfMonth()->format('Y-m-d') }}&date_to={{ now()->endOfMonth()->format('Y-m-d') }}" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all duration-200 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">This Month</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">₹{{ number_format($stats['this_month'], 0) }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('bills.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                            placeholder="Search by bill number, patient name, or phone..."
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" id="status" class="block w-full py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-slate-700 mb-2">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                        class="block w-full py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3"></div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-slate-700 mb-2">Date To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                        class="block w-full py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('bills.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-slate-700 font-medium rounded-lg transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
                <div class="text-sm text-slate-500">
                    Showing {{ $bills->firstItem() ?? 0 }} to {{ $bills->lastItem() ?? 0 }} of {{ $bills->total() }} bills
                </div>
            </div>
        </form>
    </div>

    <!-- Bills Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30">
            <h3 class="text-lg font-semibold text-slate-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                </svg>
                All Bills
            </h3>
        </div>
        <div class="overflow-x-auto">
            @if($bills->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Bill Number</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Due</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($bills as $bill)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-slate-900">{{ $bill->bill_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-violet-100 flex items-center justify-center">
                                            <span class="text-violet-600 font-semibold text-sm">{{ substr($bill->patient->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-slate-900">{{ $bill->patient->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $bill->patient->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $bill->bill_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $bill->bill_date->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-slate-900">₹{{ number_format($bill->total, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-green-600">₹{{ number_format($bill->paid_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-red-600">₹{{ number_format($bill->due_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $bill->status === 'paid' ? 'bg-green-100 text-green-800' :
                                           ($bill->status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                                           ($bill->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-slate-800')) }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('bills.show', $bill) }}" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                            title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('bills.print', $bill) }}" target="_blank" 
                                            class="p-2 text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" 
                                            title="Print Invoice">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-slate-200 bg-gray-50">
                    {{ $bills->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                    <p class="mt-4 text-slate-500 text-lg">No bills found.</p>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <p class="mt-2 text-sm text-slate-400">Try adjusting your search filters.</p>
                        <a href="{{ route('bills.index') }}" class="mt-4 inline-block text-violet-600 hover:text-indigo-800 font-medium">Clear filters</a>
                    @else
                        <a href="{{ route('bills.create') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Create Your First Bill</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
