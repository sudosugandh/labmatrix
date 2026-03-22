<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Analytics & Reports</h2>
                <p class="text-sm text-gray-500 mt-1">Business insights and performance metrics</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Period Filter -->
                <form method="GET" action="{{ route('analytics.index') }}" class="flex items-center space-x-2">
                    <select name="period" onchange="this.form.submit()" 
                        class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Last Week</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Last Month</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Last Year</option>
                    </select>
                </form>
                <a href="{{ route('analytics.export', ['period' => $period]) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Export Report
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Revenue Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
                    <h3 class="text-lg font-bold text-white">Total Revenue</h3>
                </div>
                <div class="p-6">
                    <p class="text-3xl font-bold text-gray-900">₹{{ number_format($revenue['total'], 2) }}</p>
                    <p class="text-sm text-gray-500 mt-2">From {{ $startDate }} to {{ $endDate }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600">
                    <h3 class="text-lg font-bold text-white">Paid Amount</h3>
                </div>
                <div class="p-6">
                    <p class="text-3xl font-bold text-gray-900">₹{{ number_format($revenue['paid'], 2) }}</p>
                    <p class="text-sm text-gray-500 mt-2">Collected payments</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-600">
                    <h3 class="text-lg font-bold text-white">Pending Amount</h3>
                </div>
                <div class="p-6">
                    <p class="text-3xl font-bold text-gray-900">₹{{ number_format($revenue['pending'], 2) }}</p>
                    <p class="text-sm text-gray-500 mt-2">Outstanding dues</p>
                </div>
            </div>
        </div>

        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-xl font-bold text-white">Revenue Trend</h3>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Test Statistics -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600">
                    <h3 class="text-xl font-bold text-white">Test Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($testStats['total']) }}</p>
                        <p class="text-sm text-gray-500">Total Tests Ordered</p>
                    </div>
                    @if($testStats['top_tests']->count() > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Top Tests</h4>
                            <div class="space-y-2">
                                @foreach($testStats['top_tests']->take(5) as $test)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">{{ $test['name'] }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $test['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Patient Statistics -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600">
                    <h3 class="text-xl font-bold text-white">Patient Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($patientStats['total']) }}</p>
                        <p class="text-sm text-gray-500">Total Patients</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-bold text-indigo-600">{{ number_format($patientStats['new']) }}</p>
                        <p class="text-sm text-gray-500">New Patients (Period)</p>
                    </div>
                </div>
            </div>

            <!-- Payment Statistics -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
                    <h3 class="text-xl font-bold text-white">Payment Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($paymentStats['total'], 2) }}</p>
                        <p class="text-sm text-gray-500">Total Payments</p>
                    </div>
                    @if($paymentStats['by_method']->count() > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">By Payment Method</h4>
                            <div class="space-y-2">
                                @foreach($paymentStats['by_method'] as $method => $amount)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">{{ ucfirst($method ?? 'N/A') }}</span>
                                        <span class="text-sm font-semibold text-gray-900">₹{{ number_format($amount, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Appointment Statistics -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <h3 class="text-xl font-bold text-white">Appointment Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($appointmentStats['total']) }}</p>
                        <p class="text-sm text-gray-500">Total Appointments</p>
                    </div>
                    @if($appointmentStats['by_source']->count() > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">By Source</h4>
                            <div class="space-y-2">
                                @foreach($appointmentStats['by_source'] as $source => $count)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">{{ ucfirst($source ?? 'N/A') }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Package Statistics -->
        @if($packageStats['total'] > 0)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-xl font-bold text-white">Package Statistics</h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($packageStats['total']) }}</p>
                    <p class="text-sm text-gray-500">Total Package Orders</p>
                </div>
                @if($packageStats['top_packages']->count() > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Top Packages</h4>
                        <div class="space-y-2">
                            @foreach($packageStats['top_packages'] as $package)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">{{ $package['name'] }}</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $package['count'] }} orders</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Revenue Trend Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            const revenueData = @json($revenue['trend']);
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.map(item => item.label),
                    datasets: [{
                        label: 'Revenue (₹)',
                        data: revenueData.map(item => item.revenue),
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: ₹' + context.parsed.y.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + value.toLocaleString('en-IN');
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
