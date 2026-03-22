@extends('super-admin.layout')

@section('title', 'Create New Lab - HealthMatrix Super Admin')
@section('page-title', 'Create New Lab')
@section('page-subtitle', 'Add a new diagnostic center to the platform')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
        <h3 class="text-lg font-semibold text-white">Create New Lab</h3>
        <p class="text-sm text-indigo-100 mt-1">Add a new diagnostic center to the platform</p>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('super-admin.labs.store') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Lab Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        <p class="mt-1 text-xs text-gray-500">This will also be used for the default admin user</p>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <textarea name="address" id="address" rows="3" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Lab Level *</label>
                        <select name="level" id="level" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            <option value="small" {{ old('level') === 'small' ? 'selected' : '' }}>Small</option>
                            <option value="large" {{ old('level') === 'large' ? 'selected' : '' }}>Large</option>
                        </select>
                        <p class="mt-2 text-sm text-gray-500">
                            <strong>Small:</strong> Basic features, no technician management<br>
                            <strong>Large:</strong> Full features including technician management
                        </p>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700 mb-2">Domain (Optional)</label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain') }}" 
                        placeholder="e.g., lab1.example.com"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    <p class="mt-1 text-xs text-gray-500">Add a custom domain for this lab. Can be added later.</p>
                    @error('domain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('super-admin.labs.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                    Create Lab
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
