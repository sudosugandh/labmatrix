<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Add Inventory Item</h2>
                <p class="text-sm text-gray-500 mt-1">Add a new consumable item for diagnostic tests</p>
            </div>
            <a href="{{ route('inventory.items.index') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('inventory.items.store') }}" class="bg-white rounded-xl shadow-lg p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-input name="name" label="Item Name *" placeholder="e.g., Blood Collection Tube" required />
            <x-form-input name="sku" label="SKU" placeholder="Optional stock keeping unit" />
            <x-form-input name="unit" label="Unit *" placeholder="e.g., piece, ml, box" value="piece" required />
            <x-form-input name="current_stock" label="Initial Stock" type="number" step="0.01" min="0" value="0" />
            <x-form-input name="minimum_stock" label="Minimum Stock (Reorder Level) *" type="number" step="0.01" min="0" required />
            <x-form-input name="cost" label="Cost per Unit (₹) *" type="number" step="0.01" min="0" required />
            <x-form-input name="expiry_date" label="Expiry Date" type="date" />
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('inventory.items.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <x-button-loading type="submit" class="bg-indigo-600 hover:bg-indigo-700">Create Item</x-button-loading>
        </div>
    </form>
</x-app-layout>
