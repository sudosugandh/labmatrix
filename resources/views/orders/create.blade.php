<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">New Test Order</h2>
                <p class="text-sm text-gray-500 mt-1">Create a new test order for a patient</p>
            </div>
            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <strong>Validation Errors:</strong>
                    <ul class="list-disc list-inside mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Patient Selection -->
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                    <select name="patient_id" id="patient_id" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('patient_id') border-red-500 @enderror">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} - {{ $patient->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Type *</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="order_type" value="test" {{ old('order_type', 'test') == 'test' ? 'checked' : '' }} 
                                class="text-indigo-600 focus:ring-indigo-500" onchange="toggleOrderType()">
                            <span class="ml-2 text-sm text-gray-700">Single Test</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="order_type" value="package" {{ old('order_type') == 'package' ? 'checked' : '' }}
                                class="text-indigo-600 focus:ring-indigo-500" onchange="toggleOrderType()">
                            <span class="ml-2 text-sm text-gray-700">Test Package</span>
                        </label>
                    </div>
                </div>

                <!-- Single Test Selection -->
                <div id="test-selection" class="order-type-section">
                    <label for="test_id" class="block text-sm font-medium text-gray-700 mb-2">Test *</label>
                    <select name="test_id" id="test_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('test_id') border-red-500 @enderror">
                        <option value="">Select Test</option>
                        @foreach($tests as $test)
                            <option value="{{ $test->id }}" {{ old('test_id') == $test->id ? 'selected' : '' }}>
                                {{ $test->name }} - ₹{{ number_format($test->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('test_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Package Selection -->
                <div id="package-selection" class="order-type-section" style="display: none;">
                    <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Test Package *</label>
                    <select name="package_id" id="package_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('package_id') border-red-500 @enderror">
                        <option value="">Select Package</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-tests="{{ $package->tests->count() }}" data-price="{{ $package->final_price }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - ₹{{ number_format($package->final_price, 2) }} ({{ $package->tests->count() }} tests)
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="package-info" class="mt-3 p-3 bg-indigo-50 rounded-lg" style="display: none;">
                        <p class="text-sm text-indigo-800">
                            <strong>Package includes:</strong> <span id="package-tests-count"></span> tests
                            <br>
                            <strong>Total Price:</strong> ₹<span id="package-price"></span>
                        </p>
                    </div>
                </div>

                @if(isLargeLab())
                <div>
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-2">Assign Technician</label>
                    <select name="technician_id" id="technician_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3">
                        <option value="">No Technician</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                {{ $technician->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sample_collected" {{ old('status') == 'sample_collected' ? 'selected' : '' }}>Sample Collected</option>
                        <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('orders.index') }}" class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Create Order
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleOrderType() {
            const orderType = document.querySelector('input[name="order_type"]:checked').value;
            const testSection = document.getElementById('test-selection');
            const packageSection = document.getElementById('package-selection');
            const testSelect = document.getElementById('test_id');
            const packageSelect = document.getElementById('package_id');

            if (orderType === 'test') {
                testSection.style.display = 'block';
                packageSection.style.display = 'none';
                testSelect.required = true;
                packageSelect.required = false;
                packageSelect.value = '';
                packageSelect.disabled = true; // Disable to prevent submission
            } else {
                testSection.style.display = 'none';
                packageSection.style.display = 'block';
                testSelect.required = false;
                testSelect.value = '';
                testSelect.disabled = true; // Disable to prevent submission
                packageSelect.required = true;
                packageSelect.disabled = false;
            }
        }

        // Ensure form doesn't submit disabled fields
        document.querySelector('form').addEventListener('submit', function(e) {
            const orderType = document.querySelector('input[name="order_type"]:checked').value;
            if (orderType === 'test') {
                document.getElementById('package_id').disabled = true;
            } else {
                document.getElementById('test_id').disabled = true;
            }
        });

        // Show package info when selected
        document.getElementById('package_id')?.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('package-info');
            
            if (this.value) {
                const testsCount = selected.getAttribute('data-tests');
                const price = selected.getAttribute('data-price');
                document.getElementById('package-tests-count').textContent = testsCount;
                document.getElementById('package-price').textContent = parseFloat(price).toFixed(2);
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOrderType();
            
            // Ensure package_id is disabled initially if test is selected
            const orderType = document.querySelector('input[name="order_type"]:checked');
            if (orderType && orderType.value === 'test') {
                const packageSelect = document.getElementById('package_id');
                if (packageSelect) {
                    packageSelect.disabled = true;
                }
            }
        });
    </script>
</x-app-layout>
