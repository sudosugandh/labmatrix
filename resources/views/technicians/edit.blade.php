<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Technician</h2>
            <p class="text-sm text-gray-500 mt-1">Update technician information</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600">
            <h3 class="text-xl font-bold text-white">Technician Information</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('technicians.update', $technician) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $technician->name) }}" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $technician->phone) }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $technician->email) }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                        <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $technician->specialization) }}" placeholder="e.g., Blood Tests, Microbiology"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-4">
                    <a href="{{ route('technicians.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        Update Technician
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
