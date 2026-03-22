<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Testimonials</h2>
                <p class="text-sm text-gray-500 mt-1">Manage patient testimonials for your website</p>
            </div>
            <a href="{{ route('testimonials.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Testimonial
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                All Testimonials
            </h3>
        </div>
        <div class="overflow-x-auto">
            @if($testimonials->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name / Photo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quote</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Featured</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($testimonials as $t)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($t->photo)
                                            <img src="{{ asset('storage/' . $t->photo) }}" alt="{{ $t->name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 text-indigo-600 font-semibold">{{ strtoupper(substr($t->name, 0, 1)) }}</div>
                                        @endif
                                        <span class="text-sm font-semibold text-gray-900">{{ $t->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 max-w-md line-clamp-2">{{ \Illuminate\Support\Str::limit($t->quote, 80) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($t->rating)
                                        <span class="text-amber-500">{{ str_repeat('★', (int)$t->rating) }}{{ str_repeat('☆', 5 - (int)$t->rating) }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($t->is_featured)
                                        <span class="px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">Featured</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('testimonials.edit', $t) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('testimonials.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Remove this testimonial?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $testimonials->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <p class="mt-2 font-medium">No testimonials yet</p>
                    <p class="text-sm">Add patient testimonials to show on your homepage.</p>
                    <a href="{{ route('testimonials.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                        Add Testimonial
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
