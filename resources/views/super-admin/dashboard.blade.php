@extends('super-admin.layout')

@section('title', 'Super Admin Dashboard - HealthMatrix')
@section('page-title', 'Super Admin Dashboard')
@section('page-subtitle', 'Overview of all diagnostic centers')

@section('content')
<div class="space-y-6">
    <!-- Key Metrics Cards - Professional Style -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <!-- Total Labs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Labs</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_labs']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $stats['active_labs'] }} active, {{ $stats['suspended_labs'] }} suspended</p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-emerald-100 rounded-lg group-hover:bg-emerald-200 transition-colors">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Users</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Across all labs</p>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Patients</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_patients']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Registered patients</p>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-orange-100 rounded-lg group-hover:bg-orange-200 transition-colors">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Orders</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Test orders</p>
                </div>
            </div>
        </div>

        <!-- Total Domains -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Domains</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_domains']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $stats['active_domains'] }} active</p>
                </div>
            </div>
        </div>

        <!-- Active Labs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Labs</p>
                    <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($stats['active_labs']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Currently operational</p>
                </div>
            </div>
        </div>

        <!-- Suspended Labs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Suspended Labs</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">{{ number_format($stats['suspended_labs']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Temporarily disabled</p>
                </div>
            </div>
        </div>

        <!-- Total Reports -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-teal-100 rounded-lg group-hover:bg-teal-200 transition-colors">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Reports</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_reports']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Generated reports</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lab Distribution Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Lab Levels Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-lg font-semibold text-white">Lab Levels Distribution</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-indigo-600 rounded mr-3"></div>
                            <span class="text-gray-700 font-medium">Large Labs</span>
                        </div>
                        <span class="text-2xl font-bold text-indigo-600">{{ $stats['large_labs'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gray-400 rounded mr-3"></div>
                            <span class="text-gray-700 font-medium">Small Labs</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-600">{{ $stats['small_labs'] }}</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 font-medium">Total</span>
                            <span class="font-bold text-gray-900 text-lg">{{ $stats['total_labs'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lab Status Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
                <h3 class="text-lg font-semibold text-white">Lab Status Distribution</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-600 rounded mr-3"></div>
                            <span class="text-gray-700 font-medium">Active Labs</span>
                        </div>
                        <span class="text-2xl font-bold text-green-600">{{ $stats['active_labs'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-600 rounded mr-3"></div>
                            <span class="text-gray-700 font-medium">Suspended Labs</span>
                        </div>
                        <span class="text-2xl font-bold text-red-600">{{ $stats['suspended_labs'] }}</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 font-medium">Total</span>
                            <span class="font-bold text-gray-900 text-lg">{{ $stats['total_labs'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Labs by Activity -->
    @if(isset($topLabs) && $topLabs->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
            <h3 class="text-lg font-semibold text-white">Top Labs by Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($topLabs as $index => $lab)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-indigo-600 font-bold">#{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $lab->name }}</p>
                                <p class="text-sm text-gray-500">{{ $lab->test_orders_count }} orders • {{ $lab->patients_count }} patients</p>
                            </div>
                        </div>
                        <a href="{{ route('super-admin.labs.show', $lab) }}" class="text-indigo-600 hover:text-indigo-900 font-medium flex items-center">
                            View
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800">
            <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('super-admin.labs.index') }}" class="flex items-center justify-center p-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Manage All Labs
                </a>
                <a href="{{ route('super-admin.labs.create') }}" class="flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create New Lab
                </a>
                <a href="{{ route('super-admin.labs.export') }}" class="flex items-center justify-center p-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Labs Data
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Labs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
            <h3 class="text-lg font-semibold text-white">Recent Labs</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lab Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Domains</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentLabs as $lab)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $lab->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lab->level === 'large' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($lab->level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($lab->status ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($lab->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->users_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->test_orders_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->domains_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('super-admin.labs.show', $lab) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No labs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
