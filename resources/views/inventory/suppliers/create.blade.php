<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Add Supplier</h2>
                <p class="text-sm text-gray-500 mt-1">Add a new supplier or vendor</p>
            </div>
            <a href="{{ route('inventory.suppliers.index') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('inventory.suppliers.store') }}" class="bg-white rounded-xl shadow-lg p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-input name="name" label="Supplier Name *" required />
            <x-form-input name="contact_person" label="Contact Person" />
            <x-form-input name="email" label="Email" type="email" />
            <x-form-input name="phone" label="Phone" />
            <x-form-input name="alternate_phone" label="Alternate Phone" />
            <div class="md:col-span-2">
                <x-form-textarea name="address" label="Address" rows="2" />
            </div>
            <x-form-input name="city" label="City" />
            <x-form-input name="state" label="State" />
            <x-form-input name="zip_code" label="Zip Code" />
            <x-form-input name="country" label="Country" />
            <x-form-input name="tax_id" label="Tax ID" />
            <x-form-input name="payment_terms" label="Payment Terms" placeholder="e.g., Net 30, COD" />
            <div class="md:col-span-2">
                <x-form-textarea name="notes" label="Notes" rows="3" />
            </div>
            <div class="md:col-span-2">
                <x-form-checkbox name="is_active" label="Active" checked />
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('inventory.suppliers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <x-button-loading type="submit" class="bg-indigo-600 hover:bg-indigo-700">Create Supplier</x-button-loading>
        </div>
    </form>
</x-app-layout>
