<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Offers</h2>
                <p class="text-sm text-gray-500 mt-1">Manage special offers and promos for your website</p>
            </div>
            <a href="{{ route('promos.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Offer
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-orange-50">
            <h3 class="text-lg font-semibold text-gray-900">All Offers</h3>
        </div>
        <div class="overflow-x-auto">
            @if($promos->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Order</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Valid Until</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($promos as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->sort_order }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $p->title }}</div>
                                    @if($p->short_text)
                                        <div class="text-sm text-gray-500 max-w-md line-clamp-1">{{ $p->short_text }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->valid_until ? $p->valid_until->format('M d, Y') : '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($p->is_active)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Hidden</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('promos.edit', $p) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">Edit</a>
                                    <form action="{{ route('promos.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Remove this offer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t">{{ $promos->links() }}</div>
            @else
                <div class="px-6 py-12 text-center text-gray-500">
                    <p class="font-medium">No offers yet</p>
                    <p class="text-sm mt-1">Add offers to show on your homepage.</p>
                    <a href="{{ route('promos.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">Add Offer</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
