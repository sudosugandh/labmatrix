<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Inventory Item</h2>
                <p class="text-sm text-gray-500 mt-1">Update item information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.items.show', $item) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    View
                </a>
                <a href="{{ route('inventory.items.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('inventory.items.update', $item) }}" class="bg-white rounded-xl shadow-lg p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-input name="name" label="Item Name *" :value="old('name', $item->name)" required />
            <x-form-input name="sku" label="SKU" :value="old('sku', $item->sku)" />
            <x-form-input name="unit" label="Unit *" :value="old('unit', $item->unit)" required />
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                <div class="flex items-center space-x-2">
                    <input type="text" value="{{ $item->current_stock }} {{ $item->unit }}" readonly
                        class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm px-4 py-2">
                    <span class="text-sm text-gray-500">(Use stock adjustment to change)</span>
                </div>
            </div>
            <x-form-input name="minimum_stock" label="Minimum Stock (Reorder Level) *" type="number" step="0.01" min="0" :value="old('minimum_stock', $item->minimum_stock)" required />
            <x-form-input name="cost" label="Cost per Unit (₹) *" type="number" step="0.01" min="0" :value="old('cost', $item->cost)" required />
            <x-form-input name="expiry_date" label="Expiry Date" type="date" :value="old('expiry_date', $item->expiry_date?->format('Y-m-d'))" />
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('inventory.items.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <x-button-loading type="submit" class="bg-indigo-600 hover:bg-indigo-700">Update Item</x-button-loading>
        </div>
    </form>
</x-app-layout>
