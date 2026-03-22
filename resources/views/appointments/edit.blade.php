<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Appointment</h2>
                <p class="text-sm text-gray-500 mt-1">Update appointment details</p>
            </div>
            <a href="{{ route('appointments.show', $appointment) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                    <select name="patient_id" id="patient_id" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('patient_id') border-red-500 @enderror">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} - {{ $patient->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                        <input type="date" name="appointment_date" id="appointment_date" 
                            value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('appointment_date') border-red-500 @enderror">
                        @error('appointment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                        @php
                            $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                            $timeValue = \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('H:i');
                        @endphp
                        <input type="time" name="appointment_time" id="appointment_time" 
                            value="{{ old('appointment_time', $timeValue) }}" 
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('appointment_time') border-red-500 @enderror">
                        @error('appointment_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if(isset($packages) && $packages->count() > 0)
                <div>
                    <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Test Package (Optional)</label>
                    <select name="package_id" id="package_id"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('package_id') border-red-500 @enderror">
                        <option value="">No Package (General Appointment)</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->final_price }}" data-tests="{{ $package->tests_count }}" {{ old('package_id', $appointment->package_id) == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - ₹{{ number_format($package->final_price, 2) }} ({{ $package->tests_count }} tests)
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">If package is selected and status changes to "Confirmed", test orders will be created automatically.</p>
                    <div id="package-info" class="mt-3 p-3 bg-indigo-50 rounded-lg border border-indigo-200" style="display: none;">
                        <p class="text-sm text-gray-700">
                            <strong>Package includes:</strong> <span id="package-tests-count"></span> tests
                            <br>
                            <strong>Total Price:</strong> ₹<span id="package-price"></span>
                        </p>
                    </div>
                </div>
                @endif

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                        <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror"
                        placeholder="Any additional notes or instructions...">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('appointments.show', $appointment) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        Update Appointment
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

@if(isset($packages) && $packages->count() > 0)
<script>
    document.getElementById('package_id')?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const infoDiv = document.getElementById('package-info');
        
        if (this.value && selected.getAttribute('data-price')) {
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
        const packageSelect = document.getElementById('package_id');
        if (packageSelect && packageSelect.value) {
            packageSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endif
