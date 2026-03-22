<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Technician Details</h2>
                <p class="text-sm text-gray-500 mt-1">View technician information and assigned tests</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('technicians.edit', $technician) }}" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Edit
                </a>
                <a href="{{ route('technicians.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600">
                <h3 class="text-xl font-bold text-white">Technician Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Name</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $technician->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Phone</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $technician->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $technician->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Specialization</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $technician->specialization ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Assigned Test Orders
                </h3>
            </div>
            <div class="p-6">
                @if($technician->testOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Test</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($technician->testOrders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $order->patient->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">{{ $order->test->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $order->status === 'completed' || $order->status === 'report_uploaded' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                                   ($order->status === 'sample_collected' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">No test orders assigned to this technician.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
