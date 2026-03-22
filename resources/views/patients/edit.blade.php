<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Patient</h2>
                <p class="text-sm text-gray-500 mt-1">Update patient information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
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

    <form method="POST" action="{{ route('patients.update', $patient) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <x-form-section title="Basic Information" description="Update the patient's basic personal details">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input 
                    label="Full Name" 
                    name="name" 
                    type="text" 
                    required 
                    placeholder="Enter patient's full name"
                    value="{{ old('name', $patient->name) }}"
                />

                <x-form-input 
                    label="Phone Number" 
                    name="phone" 
                    type="tel" 
                    required 
                    placeholder="Enter phone number"
                    value="{{ old('phone', $patient->phone) }}"
                />

                <x-form-input 
                    label="Email Address" 
                    name="email" 
                    type="email" 
                    placeholder="Enter email address (optional)"
                    value="{{ old('email', $patient->email) }}"
                />

                <x-form-input 
                    label="Age" 
                    name="age" 
                    type="number" 
                    placeholder="Enter age"
                    value="{{ old('age', $patient->age) }}"
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
                        <option value="male" {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $patient->gender) === 'other' ? 'selected' : '' }}>Other</option>
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
                    value="{{ old('address', $patient->address) }}"
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
                <span class="button-text">Update Patient</span>
            </button>
        </div>
    </form>
</x-app-layout>
