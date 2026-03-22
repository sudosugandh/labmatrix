<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">New Appointment</h2>
                <p class="text-sm text-gray-500 mt-1">Schedule a new patient appointment</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('appointments.store') }}" class="space-y-6">
        @csrf

        <!-- Appointment Details -->
        <x-form-section title="Appointment Details" description="Select patient and schedule the appointment">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Patient <span class="text-red-500">*</span>
                    </label>
                    <select name="patient_id" id="patient_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 transition-colors">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} - {{ $patient->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 transition-colors">
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <x-form-input 
                    label="Appointment Date" 
                    name="appointment_date" 
                    type="date" 
                    required 
                    value="{{ old('appointment_date') }}"
                    min="{{ date('Y-m-d') }}"
                />

                <x-form-input 
                    label="Appointment Time" 
                    name="appointment_time" 
                    type="time" 
                    required 
                    value="{{ old('appointment_time') }}"
                />
            </div>
        </x-form-section>

        @if(isset($packages) && $packages->count() > 0)
        <!-- Test Package -->
        <x-form-section title="Test Package (Optional)" description="Select a test package if this appointment is for a package">
            <div>
                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Test Package</label>
                <select name="package_id" id="package_id"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 transition-colors">
                    <option value="">No Package (General Appointment)</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" data-price="{{ $package->final_price }}" data-tests="{{ $package->tests_count }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} - ₹{{ number_format($package->final_price, 2) }} ({{ $package->tests_count }} tests)
                        </option>
                    @endforeach
                </select>
                @error('package_id')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">If package is selected and status is "Confirmed", test orders will be created automatically.</p>
                <div id="package-info" class="mt-3 p-4 bg-indigo-50 rounded-lg border border-indigo-200" style="display: none;">
                    <p class="text-sm text-gray-700">
                        <strong class="text-indigo-900">Package includes:</strong> <span id="package-tests-count" class="font-semibold"></span> tests
                        <br class="mt-1">
                        <strong class="text-indigo-900">Total Price:</strong> ₹<span id="package-price" class="font-semibold"></span>
                    </p>
                </div>
            </div>
        </x-form-section>
        @endif

        <!-- Additional Notes -->
        <x-form-section title="Additional Notes" description="Add any special instructions or notes for this appointment">
            <x-form-textarea 
                label="Notes" 
                name="notes" 
                rows="4"
                placeholder="Enter any special instructions or notes..."
                value="{{ old('notes') }}"
            />
        </x-form-section>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <a href="{{ route('appointments.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Create Appointment
            </button>
        </div>
    </form>

    @push('scripts')
    @if(isset($packages) && $packages->count() > 0)
    <script>
        document.getElementById('package_id').addEventListener('change', function() {
            const packageInfo = document.getElementById('package-info');
            const testsCount = document.getElementById('package-tests-count');
            const packagePrice = document.getElementById('package-price');
            
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const tests = selectedOption.getAttribute('data-tests');
                
                testsCount.textContent = tests;
                packagePrice.textContent = parseFloat(price).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                packageInfo.style.display = 'block';
            } else {
                packageInfo.style.display = 'none';
            }
        });
    </script>
    @endif
    @endpush
</x-app-layout>
