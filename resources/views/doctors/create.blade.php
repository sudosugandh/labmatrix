<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Add Doctor</h2>
                <p class="text-sm text-gray-500 mt-1">Register a new referring doctor</p>
            </div>
            <a href="{{ route('doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('doctors.store') }}" class="space-y-6">
        @csrf

        <!-- Doctor Information -->
        <x-form-section title="Doctor Information" description="Enter the doctor's personal and professional details">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input 
                    label="Full Name" 
                    name="name" 
                    type="text" 
                    required 
                    placeholder="Enter doctor's full name"
                    value="{{ old('name') }}"
                />

                <x-form-input 
                    label="Specialization" 
                    name="specialization" 
                    type="text" 
                    placeholder="e.g., Cardiologist, General Physician"
                    value="{{ old('specialization') }}"
                />

                <x-form-input 
                    label="Qualification" 
                    name="qualification" 
                    type="text" 
                    placeholder="e.g., MBBS, MD"
                    value="{{ old('qualification') }}"
                />

                <x-form-input 
                    label="Registration Number" 
                    name="registration_number" 
                    type="text" 
                    placeholder="Medical registration number"
                    value="{{ old('registration_number') }}"
                />

                <x-form-input 
                    label="Phone Number" 
                    name="phone" 
                    type="tel" 
                    placeholder="Enter phone number"
                    value="{{ old('phone') }}"
                />

                <x-form-input 
                    label="Email Address" 
                    name="email" 
                    type="email" 
                    placeholder="Enter email address"
                    value="{{ old('email') }}"
                />

                <x-form-input 
                    label="Clinic Name" 
                    name="clinic_name" 
                    type="text" 
                    placeholder="Enter clinic or hospital name"
                    value="{{ old('clinic_name') }}"
                />
            </div>

            <div class="mt-6">
                <x-form-textarea 
                    label="Address" 
                    name="address" 
                    rows="3"
                    placeholder="Enter clinic address"
                    value="{{ old('address') }}"
                />
            </div>
        </x-form-section>

        <!-- Commission Settings -->
        <x-form-section title="Commission Settings" description="Configure how commissions are calculated for this doctor">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="commission_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Commission Type <span class="text-red-500">*</span>
                    </label>
                    <select name="commission_type" id="commission_type" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 transition-colors">
                        <option value="">Select Type</option>
                        <option value="percentage" {{ old('commission_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('commission_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                    </select>
                    @error('commission_type')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <x-form-input 
                    label="Commission Rate" 
                    name="commission_rate" 
                    type="number" 
                    required 
                    placeholder="e.g., 10 for 10% or 100 for ₹100"
                    value="{{ old('commission_rate', 0) }}"
                    step="0.01"
                    min="0"
                />
            </div>
            <p class="mt-2 text-xs text-gray-500" id="commission_hint">Enter percentage (e.g., 10 for 10%) or fixed amount (e.g., 100 for ₹100)</p>
        </x-form-section>

        <x-form-section title="Doctor portal (website)" description="Doctors sign in at /doctor-portal on your lab site using their phone and this password.">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input label="Portal password" name="portal_password" type="password" autocomplete="new-password" placeholder="Min. 8 characters" />
                <x-form-input label="Confirm portal password" name="portal_password_confirmation" type="password" autocomplete="new-password" />
            </div>
            <p class="text-xs text-gray-500 mt-2">Leave blank to create the doctor without portal access until you set a password later.</p>
        </x-form-section>

        <!-- Status -->
        <x-form-section title="Status" description="Control doctor's active status">
            <x-form-checkbox 
                label="Active (Doctor can receive referrals)" 
                name="is_active" 
                value="1"
                checked="{{ old('is_active', true) }}"
            />
        </x-form-section>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <a href="{{ route('doctors.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Add Doctor
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.getElementById('commission_type').addEventListener('change', function() {
            const hint = document.getElementById('commission_hint');
            const rateInput = document.getElementById('commission_rate');
            if (this.value === 'percentage') {
                hint.textContent = 'Enter percentage (e.g., 10 for 10%)';
                rateInput.placeholder = 'e.g., 10 for 10%';
            } else if (this.value === 'fixed') {
                hint.textContent = 'Enter fixed amount in ₹ (e.g., 100 for ₹100)';
                rateInput.placeholder = 'e.g., 100 for ₹100';
            }
        });
    </script>
    @endpush
</x-app-layout>
