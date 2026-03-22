<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Add Patient</h2>
                <p class="text-sm text-gray-500 mt-1">Create a new patient record</p>
            </div>
            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('patients.store') }}" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <x-form-section title="Basic Information" description="Enter the patient's basic personal details">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input 
                    label="Full Name" 
                    name="name" 
                    type="text" 
                    required 
                    placeholder="Enter patient's full name"
                    value="{{ old('name') }}"
                />

                <x-form-input 
                    label="Phone Number" 
                    name="phone" 
                    type="tel" 
                    required 
                    placeholder="Enter phone number"
                    value="{{ old('phone') }}"
                />

                <x-form-input 
                    label="Email Address" 
                    name="email" 
                    type="email" 
                    placeholder="Enter email address (optional)"
                    value="{{ old('email') }}"
                />

                <x-form-input 
                    label="Age" 
                    name="age" 
                    type="number" 
                    placeholder="Enter age"
                    value="{{ old('age') }}"
                    min="0"
                    max="150"
                />
            </div>
        </x-form-section>

        <!-- Additional Information -->
        <x-form-section title="Additional Information" description="Optional details for better patient management">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" id="gender" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 transition-colors">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <x-form-textarea 
                    label="Address" 
                    name="address" 
                    rows="3"
                    placeholder="Enter patient's address (optional)"
                    value="{{ old('address') }}"
                />
            </div>
        </x-form-section>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <a href="{{ route('patients.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="button-text">Create Patient</span>
            </button>
        </div>
    </form>
</x-app-layout>
