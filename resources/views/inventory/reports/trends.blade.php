<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Consumption Trends</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $periodLabel }} consumption patterns</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('inventory.reports.trends') }}" class="flex items-center space-x-2">
                    <select name="type" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                        <option value="monthly" {{ $type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="weekly" {{ $type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                </form>
                <a href="{{ route('inventory.reports.index') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <canvas id="trendsChart" height="100"></canvas>
    </div>

    <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-semibold text-gray-900">Consumption Data</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Consumption</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $period)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $period['period'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($period['consumption'], 2) }} units
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('trendsChart');
        const trendsData = @json($data);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendsData.map(d => d.period),
                datasets: [{
                    label: 'Consumption',
                    data: trendsData.map(d => d.consumption),
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
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
                    title: {
                        display: true,
                        text: '{{ $periodLabel }} Consumption Trends'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Consumption (units)'
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
