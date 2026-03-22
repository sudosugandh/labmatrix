<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <x-avatar :name="$patient->name" size="lg" />
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $patient->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Patient ID: #{{ str_pad($patient->id, 6, '0', STR_PAD_LEFT) }}
                        @if($patient->created_at)
                            • Added {{ $patient->created_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
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

    <div class="space-y-6">
        <!-- Patient Information -->
        <x-detail-card 
                title="Patient Information" 
                gradient="from-indigo-500 to-purple-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </x-slot>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-detail-field 
                        label="Full Name" 
                        :value="$patient->name"
                    >
                        <x-slot name="icon">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </x-slot>
                    </x-detail-field>
                    <x-detail-field 
                        label="Phone Number" 
                        :value="$patient->phone"
                    >
                        <x-slot name="icon">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </x-slot>
                    </x-detail-field>
                    <x-detail-field 
                        label="Email Address" 
                        :value="$patient->email ?? 'N/A'"
                    >
                        <x-slot name="icon">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </x-slot>
                    </x-detail-field>
                    <x-detail-field 
                        label="Age" 
                        :value="$patient->age ? $patient->age . ' years' : 'N/A'"
                    >
                        <x-slot name="icon">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </x-slot>
                    </x-detail-field>
                    <x-detail-field 
                        label="Gender" 
                        :value="ucfirst($patient->gender ?? 'N/A')"
                    >
                        <x-slot name="icon">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </x-slot>
                    </x-detail-field>
                    <x-detail-field 
                        label="Address" 
                        :value="$patient->address ?? 'N/A'"
                    >
                        <x-slot name="icon">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </x-slot>
                    </x-detail-field>
                </dl>
        </x-detail-card>

        <!-- Test Orders History -->
        <x-detail-card 
                title="Test Orders History" 
                gradient="from-blue-500 to-cyan-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
                @if($patient->testOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Test</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($patient->testOrders->sortByDesc('created_at')->take(10) as $order)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $order->test->name }}</div>
                                            @if($order->package)
                                                <div class="text-xs text-indigo-600 mt-1">Package: {{ $order->package->name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <x-status-badge :status="$order->status" />
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                                            @if($order->report && $order->report->status === 'ready')
                                                <a href="{{ route('reports.download', $order->report) }}" class="ml-3 text-emerald-600 hover:text-emerald-900 font-medium">Download</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($patient->testOrders->count() > 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('orders.index') }}?patient_id={{ $patient->id }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                View all {{ $patient->testOrders->count() }} test orders →
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">No test orders for this patient.</p>
                        <a href="{{ route('orders.create') }}?patient_id={{ $patient->id }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            Create Test Order
                        </a>
                    </div>
                @endif
        </x-detail-card>
    </div>
</x-app-layout>
