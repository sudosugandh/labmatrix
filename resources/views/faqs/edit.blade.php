<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit FAQ</h2>
                <p class="text-sm text-gray-500 mt-1">Update question and answer</p>
            </div>
            <a href="{{ route('faqs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
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

    <form method="POST" action="{{ route('faqs.update', $faq) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-form-section title="FAQ" description="Question and answer shown on the homepage">
            <div>
                <x-form-input
                    label="Question"
                    name="question"
                    type="text"
                    required
                    placeholder="e.g., Do I need to fast before a blood test?"
                    value="{{ old('question', $faq->question) }}"
                />
            </div>
            <div class="mt-6">
                <x-form-textarea
                    label="Answer"
                    name="answer"
                    rows="5"
                    required
                    placeholder="Your answer."
                    value="{{ old('answer', $faq->answer) }}"
                />
            </div>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" value="{{ old('sort_order', $faq->sort_order) }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Show on homepage</span>
                    </label>
                </div>
            </div>
        </x-form-section>

        <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <a href="{{ route('faqs.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                Update FAQ
            </button>
        </div>
    </form>
</x-app-layout>
