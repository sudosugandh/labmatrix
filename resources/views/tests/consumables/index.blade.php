<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Test Consumables</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $test->name }} - Configure required inventory items</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tests.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Tests</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Test Info Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Information</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Test Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $test->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                    <dd class="mt-1 text-sm text-gray-900">₹{{ number_format($test->price, 2) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Add Consumable Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Consumable Item</h3>
            <form method="POST" action="{{ route('tests.consumables.store', $test) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Inventory Item *</label>
                        <select name="item_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select an item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->current_stock }} {{ $item->unit }} available)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Required *</label>
                        <input type="number" name="quantity_required" step="0.01" min="0.01" required
                            placeholder="e.g., 1, 2.5"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button-loading type="submit" class="bg-indigo-600 hover:bg-indigo-700">Add Consumable</x-button-loading>
                </div>
            </form>
        </div>

        <!-- Consumables List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">Required Consumables</h3>
            </div>
            @if($consumables->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Required</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($consumables as $consumable)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $consumable->item->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $consumable->item->sku ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $consumable->item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $consumable->quantity_required }} {{ $consumable->item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium {{ $consumable->item->current_stock < $consumable->quantity_required ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ $consumable->item->current_stock }} {{ $consumable->item->unit }}
                                        </span>
                                        @if($consumable->item->current_stock < $consumable->quantity_required)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Insufficient
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form method="POST" action="{{ route('tests.consumables.destroy', $consumable) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to remove this consumable?')" 
                                                class="text-red-600 hover:text-red-900">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="mt-4 text-gray-500 text-lg">No consumables configured for this test.</p>
                    <p class="mt-2 text-sm text-gray-400">Add consumables above to track inventory usage when this test is completed.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
