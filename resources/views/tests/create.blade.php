<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Add Test</h2>
                <p class="text-sm text-gray-500 mt-1">Create a new diagnostic test</p>
            </div>
            <a href="{{ route('tests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('tests.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Test Information -->
        <x-form-section title="Test Information" description="Enter the test details and pricing">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input 
                    label="Test Name" 
                    name="name" 
                    type="text" 
                    required 
                    placeholder="e.g., Complete Blood Count (CBC)"
                    value="{{ old('name') }}"
                />

                <x-form-input 
                    label="Price (₹)" 
                    name="price" 
                    type="number" 
                    required 
                    placeholder="0.00"
                    value="{{ old('price') }}"
                    step="0.01"
                    min="0"
                />
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Service image (optional)</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-500">Shown on the public "Our Services" page. Max 2MB. JPEG, PNG, GIF, WebP.</p>
            </div>

            <div class="mt-6">
                <x-form-textarea 
                    label="Description" 
                    name="description" 
                    rows="4"
                    placeholder="Enter test description, preparation instructions, or other relevant information. Shown on the public website."
                    value="{{ old('description') }}"
                />
            </div>
        </x-form-section>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <a href="{{ route('tests.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Create Test
            </button>
        </div>
    </form>
</x-app-layout>
