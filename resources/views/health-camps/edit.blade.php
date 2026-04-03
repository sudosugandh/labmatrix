<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit health camp</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $healthCamp->name }}</p>
            </div>
            <a href="{{ route('health-camps.show', $healthCamp) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg">Back</a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('health-camps.update', $healthCamp) }}" class="space-y-8 max-w-3xl">
        @csrf
        @method('PUT')
        <x-form-section title="Camp details" description="Update corporate link and schedule">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="corporate_account_id" class="block text-sm font-medium text-gray-700 mb-2">Corporate account</label>
                    <select name="corporate_account_id" id="corporate_account_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">— None —</option>
                        @foreach($corporates as $corp)
                            <option value="{{ $corp->id }}" {{ (string) old('corporate_account_id', $healthCamp->corporate_account_id) === (string) $corp->id ? 'selected' : '' }}>{{ $corp->name }}</option>
                        @endforeach
                    </select>
                    @error('corporate_account_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <x-form-input label="Camp name" name="name" type="text" required :value="old('name', $healthCamp->name)" />
                </div>
                <x-form-input label="Camp date" name="camp_date" type="date" :value="old('camp_date', $healthCamp->camp_date?->format('Y-m-d'))" />
                <x-form-input label="Venue" name="venue" type="text" :value="old('venue', $healthCamp->venue)" />
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach(['scheduled', 'completed', 'cancelled'] as $st)
                            <option value="{{ $st }}" {{ old('status', $healthCamp->status) === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <x-form-textarea label="Notes" name="notes" rows="3" :value="old('notes', $healthCamp->notes)" />
            </div>
        </x-form-section>

        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">Save</button>
    </form>
</x-app-layout>
