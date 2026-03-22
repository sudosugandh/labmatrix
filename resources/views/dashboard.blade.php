<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dashboard</h2>
                <p class="text-sm text-slate-500 mt-0.5">Welcome back! Here's your business overview.</p>
            </div>
            <button onclick="refreshDashboard()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-medium shadow-sm hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Key Metrics Cards - MAANG Style -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            @php
                $overview = $overview ?? [];
            @endphp

            <!-- Total Patients -->
            @php $stat = $overview['total_patients'] ?? ['value' => 0, 'change' => null]; @endphp
            <a href="{{ route('patients.index') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-xl hover:shadow-slate-200/50 hover:border-violet-200 transition-all overflow-hidden group block">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-sky-100 rounded-xl group-hover:bg-sky-200 transition-colors">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Patients</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stat['value'] ?? 0) }}</p>
                        @if(isset($stat['change']) && $stat['change'] !== null)
                            <div class="mt-2 flex items-center">
                                @if($stat['change'] >= 0)
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-green-600">{{ abs($stat['change']) }}%</span>
                                @else
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-red-600">{{ abs($stat['change']) }}%</span>
                                @endif
                                <span class="text-xs text-slate-500 ml-1">vs previous</span>
                            </div>
                        @endif
                    </div>
                </div>
            </a>

            <!-- Today's Appointments -->
            @php $stat = $overview['today_appointments'] ?? ['value' => 0, 'change' => null]; @endphp
            <a href="{{ route('appointments.index') }}?date={{ now()->format('Y-m-d') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-xl hover:shadow-slate-200/50 hover:border-emerald-200 transition-all overflow-hidden group block">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-emerald-100 rounded-xl group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Today's Appointments</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stat['value'] ?? 0) }}</p>
                        @if(isset($stat['change']) && $stat['change'] !== null)
                            <div class="mt-2 flex items-center">
                                @if($stat['change'] >= 0)
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-green-600">{{ abs($stat['change']) }}%</span>
                                @else
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-red-600">{{ abs($stat['change']) }}%</span>
                                @endif
                                <span class="text-xs text-slate-500 ml-1">vs yesterday</span>
                            </div>
                        @endif
                    </div>
                </div>
            </a>

            <!-- Pending Samples -->
            @php $stat = $overview['pending_samples'] ?? ['value' => 0, 'change' => null]; @endphp
            <a href="{{ route('orders.index') }}?sample_status=pending" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-xl hover:shadow-slate-200/50 hover:border-amber-200 transition-all overflow-hidden group block">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-amber-100 rounded-xl group-hover:bg-amber-200 transition-colors">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending Samples</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stat['value'] ?? 0) }}</p>
                    </div>
                </div>
            </a>

            <!-- Tests Processing -->
            @php $stat = $overview['tests_processing'] ?? ['value' => 0, 'change' => null]; @endphp
            <a href="{{ route('orders.index') }}?status=processing" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-xl hover:shadow-slate-200/50 hover:border-violet-200 transition-all overflow-hidden group block">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-violet-100 rounded-xl group-hover:bg-violet-200 transition-colors">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Processing</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stat['value'] ?? 0) }}</p>
                    </div>
                </div>
            </a>

            <!-- Reports Ready -->
            @php $stat = $overview['reports_ready'] ?? ['value' => 0, 'change' => null]; @endphp
            <a href="{{ route('reports.index') }}?status=ready" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-xl hover:shadow-slate-200/50 hover:border-purple-200 transition-all overflow-hidden group block">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-purple-100 rounded-xl group-hover:bg-purple-200 transition-colors">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Reports Ready</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stat['value'] ?? 0) }}</p>
                    </div>
                </div>
            </a>

            <!-- Monthly Revenue -->
            @php $stat = $overview['total_revenue'] ?? ['value' => 0, 'change' => null]; @endphp
            <a href="{{ route('bills.index') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-xl hover:shadow-slate-200/50 hover:border-green-200 transition-all overflow-hidden group block">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-emerald-100 rounded-xl group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Monthly Revenue</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">₹{{ number_format($stat['value'] ?? 0, 0) }}</p>
                        @if(isset($stat['change']) && $stat['change'] !== null)
                            <div class="mt-2 flex items-center">
                                @if($stat['change'] >= 0)
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-green-600">{{ abs($stat['change']) }}%</span>
                                @else
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-red-600">{{ abs($stat['change']) }}%</span>
                                @endif
                                <span class="text-xs text-slate-500 ml-1">vs last month</span>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        </div>

        <!-- Charts and Analytics Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue & Appointments Trend Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30">
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        7-Day Trends
                    </h3>
                </div>
                <div class="p-6">
                    <canvas id="trendsChart" height="250"></canvas>
                </div>
            </div>

            <!-- Revenue Overview -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-teal-50/50">
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Revenue Overview
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $revenue = $revenue ?? [];
                    @endphp
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                            <div>
                                <p class="text-sm font-medium text-slate-600">Today</p>
                                <p class="text-2xl font-bold text-emerald-700 mt-1">₹{{ number_format($revenue['today'] ?? 0, 2) }}</p>
                            </div>
                            <div class="p-3 bg-emerald-100 rounded-xl">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-sky-50 rounded-xl border border-sky-200">
                                <p class="text-xs font-medium text-slate-600">This Month</p>
                                <p class="text-xl font-bold text-sky-700 mt-1">₹{{ number_format($revenue['this_month'] ?? 0, 2) }}</p>
                            </div>
                            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                                <p class="text-xs font-medium text-slate-600">Pending</p>
                                <p class="text-xl font-bold text-amber-700 mt-1">₹{{ number_format($revenue['pending'] ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Test Orders -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Recent Test Orders
                    </h3>
                    <a href="{{ route('orders.index') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">View All →</a>
                </div>
                <div class="p-6">
                    @php
                        $recent_activity = $recent_activity ?? [];
                        $recentOrders = $recent_activity['orders'] ?? collect();
                    @endphp
                    @if($recentOrders->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentOrders as $order)
                                <div class="flex items-center justify-between p-3 bg-slate-50/80 rounded-xl hover:bg-violet-50/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-xl bg-violet-100 flex items-center justify-center">
                                                <span class="text-violet-600 font-semibold text-sm">{{ substr($order->patient->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $order->patient->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $order->test->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 
                                               ($order->status === 'processing' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-700') }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                        <p class="text-xs text-slate-500 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-slate-500">No recent orders</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-violet-50 to-purple-50/50">
                        <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('appointments.create') }}" class="flex items-center justify-between p-3 bg-violet-50 hover:bg-violet-100 rounded-xl transition-colors group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="text-sm font-medium text-slate-900">New Appointment</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center justify-between p-3 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-slate-900">New Test Order</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('patients.create') }}" class="flex items-center justify-between p-3 bg-sky-50 hover:bg-sky-100 rounded-xl transition-colors group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <span class="text-sm font-medium text-slate-900">Add Patient</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('bills.create') }}" class="flex items-center justify-between p-3 bg-amber-50 hover:bg-amber-100 rounded-xl transition-colors group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-slate-900">Create Bill</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Stock Alerts -->
                @php
                    $stock_alerts = $stock_alerts ?? [];
                    $lowStockCount = count($stock_alerts['low_stock'] ?? []);
                    $outOfStockCount = count($stock_alerts['out_of_stock'] ?? []);
                    $expiringCount = count($stock_alerts['expiring_soon'] ?? []);
                    $expiredCount = count($stock_alerts['expired'] ?? []);
                    $totalAlerts = $lowStockCount + $outOfStockCount + $expiringCount + $expiredCount;
                @endphp
                @if($totalAlerts > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-red-50 to-orange-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Stock Alerts
                            </h3>
                            <a href="{{ route('inventory.dashboard') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">View All →</a>
                        </div>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($outOfStockCount > 0)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                            <span class="text-red-600 font-bold text-xs">{{ $outOfStockCount }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Out of Stock</p>
                                        <p class="text-xs text-slate-500">Items need immediate attention</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lowStockCount > 0)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <span class="text-yellow-600 font-bold text-xs">{{ $lowStockCount }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Low Stock</p>
                                        <p class="text-xs text-slate-500">Below minimum threshold</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($expiringCount > 0 || $expiredCount > 0)
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                                            <span class="text-orange-600 font-bold text-xs">{{ $expiringCount + $expiredCount }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Expiring/Expired</p>
                                        <p class="text-xs text-slate-500">Items expiring soon or expired</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Today's Summary -->
                @php
                    $appointments = $appointments ?? [];
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-teal-50/50">
                        <h3 class="text-lg font-semibold text-slate-900">Today's Summary</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Appointments</span>
                            <span class="text-lg font-bold text-slate-900">{{ $appointments['today'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">This Week</span>
                            <span class="text-lg font-bold text-slate-900">{{ $appointments['this_week'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Upcoming</span>
                            <span class="text-lg font-bold text-slate-900">{{ $appointments['upcoming'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Trends Chart
        const trendsCtx = document.getElementById('trendsChart');
        if (trendsCtx) {
            const trendsData = @json($trends ?? ['days' => [], 'appointments' => [], 'revenue' => []]);
            
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: trendsData.days || [],
                    datasets: [
                        {
                            label: 'Appointments',
                            data: trendsData.appointments || [],
                            borderColor: 'rgb(124, 58, 237)',
                            backgroundColor: 'rgba(124, 58, 237, 0.1)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Revenue (₹)',
                            data: trendsData.revenue || [],
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        function refreshDashboard() {
            window.location.reload();
        }
    </script>
    @endpush
</x-app-layout>
