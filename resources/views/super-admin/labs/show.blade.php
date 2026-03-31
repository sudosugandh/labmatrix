@extends('super-admin.layout')

@section('title', $lab->name . ' - HealthMatrix Super Admin')
@section('page-title', $lab->name)
@section('page-subtitle', 'Lab Details and Management')

@section('content')
<div class="space-y-6">
    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <div>
            @if(($lab->status ?? 'active') === 'suspended')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Suspended
                </span>
            @endif
        </div>
        <div class="flex gap-2">
            @if(($lab->status ?? 'active') === 'suspended')
                <form method="POST" action="{{ route('super-admin.labs.activate', $lab) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        Activate Lab
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('super-admin.labs.suspend', $lab) }}" class="inline" onsubmit="return confirm('Are you sure you want to suspend this lab?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        Suspend Lab
                    </button>
                </form>
            @endif
            <a href="{{ route('super-admin.labs.edit', $lab) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                Edit Lab
            </a>
            <form method="POST" action="{{ route('super-admin.labs.destroy', $lab) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this lab? This will delete all associated data including patients, orders, and reports. This action cannot be undone!');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                    Delete Lab
                </button>
            </form>
            <a href="{{ route('super-admin.labs.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back to Labs
            </a>
        </div>
    </div>

    <!-- Lab Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
            <h3 class="text-lg font-semibold text-white">Lab Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Lab Name</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $lab->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $lab->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Phone</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $lab->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Level</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lab->level === 'large' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($lab->level) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($lab->status ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($lab->status ?? 'active') }}
                    </span>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Address</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $lab->address }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Users</p>
            <p class="text-2xl font-bold text-gray-900">{{ $lab->users_count }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Patients</p>
            <p class="text-2xl font-bold text-gray-900">{{ $lab->patients_count }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Test Orders</p>
            <p class="text-2xl font-bold text-gray-900">{{ $lab->test_orders_count }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Reports</p>
            <p class="text-2xl font-bold text-gray-900">{{ $lab->reports_count }}</p>
        </div>
    </div>

    <!-- Change Lab Level -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600">
            <h3 class="text-lg font-semibold text-white">Change Lab Level</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('super-admin.labs.update-level', $lab) }}">
                @csrf
                @method('PATCH')
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Lab Level</label>
                        <select name="level" id="level" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            <option value="small" {{ $lab->level === 'small' ? 'selected' : '' }}>Small</option>
                            <option value="large" {{ $lab->level === 'large' ? 'selected' : '' }}>Large</option>
                        </select>
                        <p class="mt-2 text-sm text-gray-500">
                            <strong>Small:</strong> Basic features, no technician management<br>
                            <strong>Large:</strong> Full features including technician management
                        </p>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                            Update Level
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Domains -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Domains ({{ $lab->domains_count }})</h3>
            <button onclick="document.getElementById('add-domain-form').classList.toggle('hidden')" 
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-1 px-4 rounded-lg transition-all">
                + Add Domain
            </button>
        </div>
        <div class="p-6">
            <!-- Add Domain Form -->
            <div id="add-domain-form" class="hidden mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <form method="POST" action="{{ route('super-admin.labs.domains.store', $lab) }}" class="space-y-3">
                    @csrf
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[200px]">
                            <label for="domain" class="block text-sm font-medium text-gray-700 mb-2">Domain</label>
                            <input type="text" name="domain" id="domain" placeholder="e.g., lab1.example.com" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-gray-300">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                                Add Domain
                            </button>
                            <button type="button" onclick="document.getElementById('add-domain-form').classList.add('hidden')" 
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </div>
                    @error('domain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            @if($lab->domains->count() > 0)
                <div class="space-y-2">
                    @foreach($lab->domains as $domain)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $domain->domain }}</span>
                                <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full {{ $domain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $domain->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <form method="POST" action="{{ route('super-admin.labs.domains.update', [$lab, $domain]) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $domain->is_active ? 0 : 1 }}">
                                    <button type="submit" class="px-3 py-1 text-sm font-medium rounded-lg {{ $domain->is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }} transition-colors">
                                        {{ $domain->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('super-admin.labs.domains.destroy', [$lab, $domain]) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this domain? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm font-medium text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No domains configured for this lab.</p>
            @endif
        </div>
    </div>

    <!-- Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Users ({{ $lab->users_count }})</h3>
            <button onclick="document.getElementById('add-user-form').classList.toggle('hidden')" 
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-1 px-4 rounded-lg transition-all">
                + Add User
            </button>
        </div>
        <div class="p-6">
            <!-- Add User Form -->
            <div id="add-user-form" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                <form method="POST" action="{{ route('super-admin.labs.users.store', $lab) }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" name="name" placeholder="Full Name" required
                            class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        <input type="email" name="email" placeholder="Email" required
                            class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        <input type="password" name="password" placeholder="Password" required
                            class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                            class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        <select name="role" required
                            class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-lg">
                                Add User
                            </button>
                            <button type="button" onclick="document.getElementById('add-user-form').classList.add('hidden')" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if($lab->users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($lab->users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form method="POST" action="{{ route('super-admin.labs.users.destroy', [$lab, $user]) }}" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No users found for this lab.</p>
            @endif
        </div>
    </div>

    <!-- Lab Settings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
            <h3 class="text-lg font-semibold text-white">Lab Settings</h3>
            <p class="text-sm text-indigo-100 mt-1">Manage lab website settings and branding</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('super-admin.labs.settings.update', $lab) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">Lab / brand name</label>
                        <input type="text" name="brand_name" id="brand_name" value="{{ old('brand_name', optional($settings)->brand_name ?? $lab->name) }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Primary color</label>
                        <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', optional($settings)->primary_color ?? '#3B82F6') }}"
                            class="block w-full h-10 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">Secondary color</label>
                        <input type="color" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', optional($settings)->secondary_color ?? '#8b5cf6') }}"
                            class="block w-full h-10 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', optional($settings)->phone ?? $lab->phone) }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', optional($settings)->email ?? $lab->email) }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="2"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">{{ old('address', optional($settings)->address ?? $lab->address) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        Update Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lab Feature Flags -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
            <h3 class="text-lg font-semibold text-white">Feature Flags</h3>
            <p class="text-sm text-green-100 mt-1">Enable or disable features for this lab</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('super-admin.labs.features.update', $lab) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="online_booking_enabled" class="text-sm font-medium text-gray-900">Online Booking</label>
                            <p class="text-xs text-gray-500 mt-1">Allow patients to book appointments online</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="online_booking_enabled" value="1" 
                                {{ old('online_booking_enabled', $settings->online_booking_enabled ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="online_reports_enabled" class="text-sm font-medium text-gray-900">Online Reports</label>
                            <p class="text-xs text-gray-500 mt-1">Allow patients to view reports online</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="online_reports_enabled" value="1" 
                                {{ old('online_reports_enabled', $settings->online_reports_enabled ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="notifications_enabled" class="text-sm font-medium text-gray-900">Notifications</label>
                            <p class="text-xs text-gray-500 mt-1">Enable email and SMS notifications</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notifications_enabled" value="1" 
                                {{ old('notifications_enabled', $settings->notifications_enabled ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="analytics_enabled" class="text-sm font-medium text-gray-900">Analytics</label>
                            <p class="text-xs text-gray-500 mt-1">Enable analytics and reporting features</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="analytics_enabled" value="1" 
                                {{ old('analytics_enabled', $settings->analytics_enabled ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="payments_enabled" class="text-sm font-medium text-gray-900">Payments</label>
                            <p class="text-xs text-gray-500 mt-1">Enable online payment processing</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="payments_enabled" value="1" 
                                {{ old('payments_enabled', $settings->payments_enabled ?? false) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        Update Features
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lab Activity Monitoring -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600">
            <h3 class="text-lg font-semibold text-white">Activity Monitoring</h3>
            <p class="text-sm text-blue-100 mt-1">Track lab usage and activity</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activityData['total_orders'] }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Reports</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activityData['total_reports'] }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">This Month Orders</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $activityData['monthly_orders'] }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">This Month Reports</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $activityData['monthly_reports'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Recent Activity</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Lab Created</p>
                                <p class="text-xs text-gray-500">{{ $activityData['lab_created_at']->format('M d, Y') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        @if($activityData['recent_user'])
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Latest User Added</p>
                                <p class="text-xs text-gray-500">{{ $activityData['recent_user'] }} - {{ $activityData['recent_user_date']->format('M d, Y') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last 7 Days Orders</p>
                                <p class="text-xs text-gray-500">{{ $activityData['last_7_days_orders'] }} orders</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last 7 Days Reports</p>
                                <p class="text-xs text-gray-500">{{ $activityData['last_7_days_reports'] }} reports</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Recent Orders</h4>
                    @if($activityData['recent_orders']->count() > 0)
                        <div class="space-y-2">
                            @foreach($activityData['recent_orders'] as $order)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 p-3 bg-gray-50 rounded-lg">No recent orders</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Lab Subscription & Billing -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600">
            <h3 class="text-lg font-semibold text-white">Subscription & Billing</h3>
            <p class="text-sm text-purple-100 mt-1">Manage lab subscription plan and billing information</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('super-admin.labs.subscription.update', $lab) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="subscription_plan" class="block text-sm font-medium text-gray-700 mb-2">Subscription Plan *</label>
                        <select name="subscription_plan" id="subscription_plan" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            <option value="trial" {{ old('subscription_plan', $lab->subscription_plan ?? 'trial') === 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="basic" {{ old('subscription_plan', $lab->subscription_plan ?? 'trial') === 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="pro" {{ old('subscription_plan', $lab->subscription_plan ?? 'trial') === 'pro' ? 'selected' : '' }}>Pro</option>
                            <option value="enterprise" {{ old('subscription_plan', $lab->subscription_plan ?? 'trial') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        </select>
                    </div>
                    <div>
                        <label for="subscription_status" class="block text-sm font-medium text-gray-700 mb-2">Subscription Status *</label>
                        <select name="subscription_status" id="subscription_status" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            <option value="active" {{ old('subscription_status', $lab->subscription_status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ old('subscription_status', $lab->subscription_status ?? 'active') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="suspended" {{ old('subscription_status', $lab->subscription_status ?? 'active') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="cancelled" {{ old('subscription_status', $lab->subscription_status ?? 'active') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label for="subscription_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="subscription_start_date" id="subscription_start_date" 
                            value="{{ old('subscription_start_date', $lab->subscription_start_date?->format('Y-m-d')) }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="subscription_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="subscription_end_date" id="subscription_end_date" 
                            value="{{ old('subscription_end_date', $lab->subscription_end_date?->format('Y-m-d')) }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="monthly_fee" class="block text-sm font-medium text-gray-700 mb-2">Monthly Fee</label>
                        <input type="number" name="monthly_fee" id="monthly_fee" step="0.01" min="0"
                            value="{{ old('monthly_fee', $lab->monthly_fee) }}"
                            placeholder="0.00"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label for="billing_notes" class="block text-sm font-medium text-gray-700 mb-2">Billing Notes</label>
                        <textarea name="billing_notes" id="billing_notes" rows="3"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                            placeholder="Internal notes about billing, payments, etc.">{{ old('billing_notes', $lab->billing_notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        Update Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lab Support Notes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">Support Notes ({{ $lab->support_notes_count }})</h3>
                <p class="text-sm text-orange-100 mt-1">Internal notes and comments for support tracking</p>
            </div>
            <button onclick="document.getElementById('add-note-form').classList.toggle('hidden')" 
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-1 px-4 rounded-lg transition-all">
                + Add Note
            </button>
        </div>
        <div class="p-6">
            <!-- Add Note Form -->
            <div id="add-note-form" class="hidden mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <form method="POST" action="{{ route('super-admin.labs.support-notes.store', $lab) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">Note *</label>
                        <textarea name="note" id="note" rows="3" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                            placeholder="Add a support note..."></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                            <select name="type" id="type" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                <option value="general">General</option>
                                <option value="support">Support</option>
                                <option value="billing">Billing</option>
                                <option value="technical">Technical</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_important" id="is_important" value="1"
                                class="rounded border-gray-300">
                            <label for="is_important" class="ml-2 text-sm text-gray-700">Mark as Important</label>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                            Add Note
                        </button>
                        <button type="button" onclick="document.getElementById('add-note-form').classList.add('hidden')" 
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            @if($lab->supportNotes->count() > 0)
                <div class="space-y-3">
                    @foreach($lab->supportNotes as $note)
                        <div class="p-4 bg-gray-50 rounded-lg border {{ $note->is_important ? 'border-orange-300 bg-orange-50' : 'border-gray-200' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $note->is_important ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($note->type) }}
                                        </span>
                                        @if($note->is_important)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Important
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500">
                                            by {{ $note->creator->name ?? 'Unknown' }} • {{ $note->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $note->note }}</p>
                                </div>
                                <form method="POST" action="{{ route('super-admin.labs.support-notes.destroy', [$lab, $note]) }}" 
                                      class="inline ml-3"
                                      onsubmit="return confirm('Are you sure you want to delete this note?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No support notes yet. Add one to track important information about this lab.</p>
            @endif
        </div>
    </div>

    <!-- Lab Data Export -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800">
            <h3 class="text-lg font-semibold text-white">Data Export</h3>
            <p class="text-sm text-gray-300 mt-1">Export all lab data for backup or migration</p>
        </div>
        <div class="p-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Warning:</strong> This will export all lab data including users, domains, settings, and support notes. Patient data, orders, and reports are not included in the export for privacy reasons.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Export Lab Data</p>
                    <p class="text-xs text-gray-500 mt-1">Download a JSON file containing all lab configuration and metadata</p>
                </div>
                <a href="{{ route('super-admin.labs.export-data', $lab) }}" 
                   class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
