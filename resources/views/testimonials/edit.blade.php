<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Testimonial</h2>
                <p class="text-sm text-gray-500 mt-1">Update testimonial details</p>
            </div>
            <a href="{{ route('testimonials.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <x-form-section title="Testimonial" description="Name, quote, optional photo and rating">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input
                    label="Name"
                    name="name"
                    type="text"
                    required
                    placeholder="e.g., Rajesh K."
                    value="{{ old('name', $testimonial->name) }}"
                />
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating (1–5, optional)</label>
                    <select name="rating" id="rating" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">No rating</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <x-form-textarea
                    label="Quote"
                    name="quote"
                    rows="4"
                    required
                    placeholder="What did the patient say?"
                    value="{{ old('quote', $testimonial->quote) }}"
                />
            </div>
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Photo (optional)</label>
                @if($testimonial->photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->name }}" class="h-16 w-16 rounded-full object-cover border border-gray-200">
                        <p class="mt-1 text-xs text-gray-500">Current photo. Upload a new file to replace.</p>
                    </div>
                @endif
                <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" value="{{ old('sort_order', $testimonial->sort_order) }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Featured on homepage</span>
                    </label>
                </div>
            </div>
        </x-form-section>

        <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <a href="{{ route('testimonials.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                Update Testimonial
            </button>
        </div>
    </form>
</x-app-layout>
