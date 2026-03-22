<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">Add time slot</h2>
        <p class="text-sm text-gray-500 mt-1">One row = one recurring time on a weekday (lab timezone).</p>
    </x-slot>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <form method="POST" action="{{ route('appointment-slots.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="weekday" class="block text-sm font-medium text-gray-700 mb-1">Weekday *</label>
                <select name="weekday" id="weekday" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($weekdays as $num => $label)
                        <option value="{{ $num }}" {{ (int) old('weekday', 1) === $num ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('weekday')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start time *</label>
                <input type="time" name="start_time" id="start_time" value="{{ old('start_time', '09:00') }}" required
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('start_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="max_bookings" class="block text-sm font-medium text-gray-700 mb-1">Max bookings (same slot, same day) *</label>
                <input type="number" name="max_bookings" id="max_bookings" value="{{ old('max_bookings', 1) }}" min="1" max="100" required
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('max_bookings')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Label (optional)</label>
                <input type="text" name="label" id="label" value="{{ old('label') }}" placeholder="e.g. Morning slot"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active (shown on booking form)</label>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">Save</button>
                <a href="{{ route('appointment-slots.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
