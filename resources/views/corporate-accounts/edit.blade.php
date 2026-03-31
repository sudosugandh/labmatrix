<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit corporate account</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $corporateAccount->name }}</p>
            </div>
            <a href="{{ route('corporate-accounts.show', $corporateAccount) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg">Back</a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('corporate-accounts.update', $corporateAccount) }}" class="space-y-8 max-w-3xl">
        @csrf
        @method('PUT')
        <x-form-section title="Organization" description="Company or camp organizer details">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input label="Organization name" name="name" type="text" required :value="old('name', $corporateAccount->name)" />
                <x-form-input label="Contact name" name="contact_name" type="text" :value="old('contact_name', $corporateAccount->contact_name)" />
                <x-form-input label="Phone (login)" name="phone" type="tel" :value="old('phone', $corporateAccount->phone)" />
                <x-form-input label="Email" name="email" type="email" :value="old('email', $corporateAccount->email)" />
            </div>
            <div class="mt-6">
                <x-form-textarea label="Notes" name="notes" rows="3" :value="old('notes', $corporateAccount->notes)" />
            </div>
        </x-form-section>

        <x-form-section title="Portal access" description="Leave password blank to keep the current password">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="portal_password" class="block text-sm font-medium text-gray-700 mb-2">New portal password</label>
                    <input type="password" name="portal_password" id="portal_password" autocomplete="new-password"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('portal_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="portal_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm password</label>
                    <input type="password" name="portal_password_confirmation" id="portal_password_confirmation" autocomplete="new-password"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </x-form-section>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-indigo-600" {{ old('is_active', $corporateAccount->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="text-sm text-gray-700">Active</label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">Save</button>
        </div>
    </form>
</x-app-layout>
