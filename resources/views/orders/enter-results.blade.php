<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Enter Test Results</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} • {{ $order->test->name }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Order
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Patient Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-lg font-semibold text-white">Patient Information</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Patient Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->patient->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->patient->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Age / Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $order->patient->age ?? 'N/A' }} / {{ $order->patient->gender ?? 'N/A' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Results Entry Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600">
                <h3 class="text-lg font-semibold text-white">Test Parameters</h3>
                <p class="text-sm text-green-100 mt-1">Enter results for each parameter. Values will be automatically evaluated against reference ranges.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('orders.save-results', $order) }}" id="results-form">
                    @csrf
                    
                    <div class="space-y-4">
                        @forelse($results as $result)
                            <div class="p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition-colors {{ $result->status === 'critical' ? 'bg-red-50 border-red-300' : ($result->status === 'high' || $result->status === 'low' ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50') }}">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <!-- Parameter Name -->
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-semibold text-gray-900">
                                        {{ $result->parameter_name }}
                                        @if($result->status !== 'normal' && $result->result_value)
                                            <span class="ml-2 px-2 py-0.5 text-xs font-bold rounded-full 
                                                {{ $result->status === 'critical' ? 'bg-red-600 text-white' : 
                                                   ($result->status === 'high' ? 'bg-orange-600 text-white' : 'bg-blue-600 text-white') }}">
                                                {{ strtoupper($result->status_flag) }}
                                            </span>
                                        @endif
                                    </label>
                                    @if($result->testParameter && $result->testParameter->method_name)
                                        <p class="text-xs text-gray-500 mt-1">Method: {{ $result->testParameter->method_name }}</p>
                                    @endif
                                </div>

                                <!-- Result Value Input -->
                                <div class="md:col-span-3">
                                    <input 
                                        type="text" 
                                        name="results[{{ $loop->index }}][result_value]" 
                                        value="{{ old("results.{$loop->index}.result_value", $result->result_value) }}"
                                        placeholder="Enter value"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 {{ $result->status === 'critical' ? 'border-red-300 bg-red-50' : ($result->status === 'high' || $result->status === 'low' ? 'border-yellow-300 bg-yellow-50' : '') }}"
                                        onchange="evaluateResult(this, {{ $loop->index }})"
                                    >
                                    <input type="hidden" name="results[{{ $loop->index }}][result_id]" value="{{ $result->id }}">
                                </div>

                                <!-- Unit -->
                                <div class="md:col-span-2">
                                    <span class="text-sm text-gray-600">{{ $result->unit ?? 'N/A' }}</span>
                                </div>

                                <!-- Reference Range -->
                                <div class="md:col-span-2">
                                    <span class="text-sm text-gray-600">{{ $result->reference_range ?? 'N/A' }}</span>
                                </div>

                                <!-- Status Indicator -->
                                <div class="md:col-span-2">
                                    <span id="status-{{ $loop->index }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $result->status === 'normal' ? 'bg-green-100 text-green-800' : 
                                           ($result->status === 'critical' ? 'bg-red-100 text-red-800' : 
                                           ($result->status === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($result->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>No parameters defined for this test. Please add parameters in test settings.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($results->count() > 0)
                        <div class="mt-6 flex items-center justify-end space-x-3 border-t border-gray-200 pt-6">
                            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                                Save Results
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Legend -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-blue-900 mb-2">Status Indicators</h4>
            <div class="flex flex-wrap gap-4 text-sm">
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    <span class="text-gray-700">Normal</span>
                </span>
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-gray-700">Low (L)</span>
                </span>
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                    <span class="text-gray-700">High (H)</span>
                </span>
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    <span class="text-gray-700">Critical (C)</span>
                </span>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Note: Real-time evaluation would require AJAX call to backend
        // For now, this is a placeholder. The actual evaluation happens on the server.
        function evaluateResult(input, index) {
            // Visual feedback - actual evaluation happens server-side
            const value = input.value;
            if (value) {
                // Remove previous status classes
                input.classList.remove('border-red-300', 'bg-red-50', 'border-yellow-300', 'bg-yellow-50');
                // Add processing class
                input.classList.add('border-indigo-300');
            } else {
                input.classList.remove('border-red-300', 'bg-red-50', 'border-yellow-300', 'bg-yellow-50', 'border-indigo-300');
            }
        }
    </script>
    @endpush
</x-app-layout>
