<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Test Package</h2>
                <p class="text-sm text-gray-500 mt-1">Update package details and tests</p>
            </div>
            <a href="{{ route('test-packages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('test-packages.update', $testPackage) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Package Information -->
        <x-form-section title="Package Information" description="Update package name and description">
            <div class="grid grid-cols-1 gap-6">
                <x-form-input 
                    label="Package Name" 
                    name="name" 
                    type="text" 
                    required 
                    placeholder="e.g., Full Body Checkup, Diabetes Package"
                    value="{{ old('name', $testPackage->name) }}"
                />

                <x-form-textarea 
                    label="Description" 
                    name="description" 
                    rows="4"
                    placeholder="Describe what this package includes..."
                    value="{{ old('description', $testPackage->description) }}"
                />
            </div>
        </x-form-section>

        <!-- Pricing -->
        <x-form-section title="Pricing" description="Update package price and discount">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-form-input 
                    label="Base Price (₹)" 
                    name="price" 
                    type="number" 
                    required 
                    value="{{ old('price', $testPackage->price) }}"
                    step="0.01"
                    min="0"
                />

                <x-form-input 
                    label="Discount (%)" 
                    name="discount" 
                    type="number" 
                    value="{{ old('discount', $testPackage->discount) }}"
                    step="0.01"
                    min="0"
                    max="100"
                />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Final Price (₹)</label>
                    <div id="final-price-display" class="block w-full rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 px-4 py-3 text-xl font-bold text-indigo-700">
                        ₹{{ number_format($testPackage->final_price, 2) }}
                    </div>
                </div>
            </div>
        </x-form-section>

        <!-- Test Selection -->
        <x-form-section title="Select Tests" description="Update which tests are included in this package">
            <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto bg-gray-50">
                @if($tests->count() > 0)
                    <div class="space-y-2">
                        @foreach($tests as $test)
                            <label class="flex items-center p-3 hover:bg-white rounded-lg cursor-pointer transition-colors border border-transparent hover:border-indigo-200">
                                <input type="checkbox" name="test_ids[]" value="{{ $test->id }}" 
                                    {{ in_array($test->id, old('test_ids', $selectedTestIds)) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <div class="ml-3 flex-1">
                                    <div class="text-sm font-semibold text-gray-900">{{ $test->name }}</div>
                                    @if($test->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($test->description, 60) }}</div>
                                    @endif
                                    <div class="text-sm text-indigo-600 font-bold mt-1">₹{{ number_format($test->price, 2) }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-8">No tests available.</p>
                @endif
            </div>
            @error('test_ids')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </x-form-section>

        <!-- Settings -->
        <x-form-section title="Settings" description="Configure package visibility and ordering">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-checkbox 
                    label="Package is active" 
                    name="is_active" 
                    value="1"
                    checked="{{ old('is_active', $testPackage->is_active) }}"
                />

                <x-form-input 
                    label="Sort Order" 
                    name="sort_order" 
                    type="number" 
                    value="{{ old('sort_order', $testPackage->sort_order) }}"
                    min="0"
                />
            </div>
        </x-form-section>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <a href="{{ route('test-packages.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Package
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            const discountInput = document.getElementById('discount');
            const finalPriceDisplay = document.getElementById('final-price-display');

            function calculateFinalPrice() {
                const price = parseFloat(priceInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                const finalPrice = discount > 0 ? price - (price * discount / 100) : price;
                finalPriceDisplay.textContent = '₹' + finalPrice.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }

            priceInput.addEventListener('input', calculateFinalPrice);
            discountInput.addEventListener('input', calculateFinalPrice);
        });
    </script>
    @endpush
</x-app-layout>
