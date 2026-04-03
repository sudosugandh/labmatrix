<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Offer</h2>
                <p class="text-sm text-gray-500 mt-1">Update offer details</p>
            </div>
            <a href="{{ route('promos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg">Back</a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('promos.update', $promo) }}" class="space-y-6">
        @csrf
        @method('PUT')
        <x-form-section title="Offer" description="Title, short text, optional link and validity">
            <x-form-input label="Title" name="title" type="text" required value="{{ old('title', $promo->title) }}" />
            <div class="mt-6">
                <x-form-textarea label="Short description" name="short_text" rows="3" value="{{ old('short_text', $promo->short_text) }}" />
            </div>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input label="Link URL (optional)" name="link" type="text" value="{{ old('link', $promo->link) }}" />
                <div>
                    <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-2">Valid from (optional)</label>
                    <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from', $promo->valid_from?->format('Y-m-d')) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-2">Valid until (optional)</label>
                    <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', $promo->valid_until?->format('Y-m-d')) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div class="mt-6 flex items-center gap-6">
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promo->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Show on homepage</span>
                </label>
                <div class="flex items-center gap-2">
                    <label for="sort_order" class="text-sm text-gray-700">Order</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" value="{{ old('sort_order', $promo->sort_order) }}" class="w-20 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </x-form-section>
        <div class="flex justify-end gap-4 bg-white rounded-xl border border-gray-200 p-6">
            <a href="{{ route('promos.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">Update Offer</button>
        </div>
    </form>
</x-app-layout>
