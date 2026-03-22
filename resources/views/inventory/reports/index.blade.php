<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Inventory Reports & Analytics</h2>
                <p class="text-sm text-gray-500 mt-1">Comprehensive insights into your inventory usage and costs</p>
            </div>
            <a href="{{ route('inventory.dashboard') }}" class="text-gray-600 hover:text-gray-900">← Back to Dashboard</a>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Items" :value="$stats['total_items']" icon="package" color="indigo" />
        <x-stat-card title="Total Adjustments" :value="$stats['total_adjustments']" icon="document-text" color="blue" />
        <x-stat-card title="Total Consumption" :value="number_format($stats['total_consumption'], 2)" icon="arrow-down" color="red" />
        <x-stat-card title="Total Stock Value" :value="'₹' . number_format($stats['total_value'], 2)" icon="currency-dollar" color="green" />
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Usage Report -->
        <a href="{{ route('inventory.reports.usage') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Usage Report</h3>
            <p class="text-sm text-gray-500">View most consumed items and consumption rates</p>
        </a>

        <!-- Consumption Trends -->
        <a href="{{ route('inventory.reports.trends') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Consumption Trends</h3>
            <p class="text-sm text-gray-500">Monthly and weekly consumption patterns</p>
        </a>

        <!-- Cost Analysis -->
        <a href="{{ route('inventory.reports.cost-analysis') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Cost Analysis</h3>
            <p class="text-sm text-gray-500">Calculate cost per test and profitability</p>
        </a>

        <!-- Reorder Recommendations -->
        <a href="{{ route('inventory.reports.reorder') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reorder Recommendations</h3>
            <p class="text-sm text-gray-500">Smart recommendations for restocking</p>
        </a>

        <!-- Expiry Tracking -->
        <a href="{{ route('inventory.reports.expiry') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Expiry Tracking</h3>
            <p class="text-sm text-gray-500">Monitor items expiring soon</p>
        </a>
    </div>
</x-app-layout>
