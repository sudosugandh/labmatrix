<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Supplier</h2>
                <p class="text-sm text-gray-500 mt-1">Update supplier information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.suppliers.show', $supplier) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    View
                </a>
                <a href="{{ route('inventory.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('inventory.suppliers.update', $supplier) }}" class="bg-white rounded-xl shadow-lg p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-input name="name" label="Supplier Name *" :value="old('name', $supplier->name)" required />
            <x-form-input name="contact_person" label="Contact Person" :value="old('contact_person', $supplier->contact_person)" />
            <x-form-input name="email" label="Email" type="email" :value="old('email', $supplier->email)" />
            <x-form-input name="phone" label="Phone" :value="old('phone', $supplier->phone)" />
            <x-form-input name="alternate_phone" label="Alternate Phone" :value="old('alternate_phone', $supplier->alternate_phone)" />
            <div class="md:col-span-2">
                <x-form-textarea name="address" label="Address" rows="2" :value="old('address', $supplier->address)" />
            </div>
            <x-form-input name="city" label="City" :value="old('city', $supplier->city)" />
            <x-form-input name="state" label="State" :value="old('state', $supplier->state)" />
            <x-form-input name="zip_code" label="Zip Code" :value="old('zip_code', $supplier->zip_code)" />
            <x-form-input name="country" label="Country" :value="old('country', $supplier->country)" />
            <x-form-input name="tax_id" label="Tax ID" :value="old('tax_id', $supplier->tax_id)" />
            <x-form-input name="payment_terms" label="Payment Terms" placeholder="e.g., Net 30, COD" :value="old('payment_terms', $supplier->payment_terms)" />
            <div class="md:col-span-2">
                <x-form-textarea name="notes" label="Notes" rows="3" :value="old('notes', $supplier->notes)" />
            </div>
            <div class="md:col-span-2">
                <x-form-checkbox name="is_active" label="Active" :checked="old('is_active', $supplier->is_active)" />
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('inventory.suppliers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <x-button-loading type="submit" class="bg-indigo-600 hover:bg-indigo-700">Update Supplier</x-button-loading>
        </div>
    </form>
</x-app-layout>
