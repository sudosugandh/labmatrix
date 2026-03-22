<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Doctors</h2>
                <p class="text-sm text-gray-500 mt-1">Manage referring doctors and commissions</p>
            </div>
            <a href="{{ route('doctors.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 shadow-lg shadow-violet-500/25 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Doctor
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800" role="alert">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Doctors</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['active']) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['inactive']) }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Referrals</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_referrals']) }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('doctors.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                            placeholder="Search by name, specialization, phone, email, or clinic..."
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-violet-500/20 focus:border-violet-500">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="block w-full py-2 border border-slate-300 rounded-lg focus:ring-violet-500/20 focus:border-violet-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 shadow-lg shadow-violet-500/25 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-gray-700 font-medium rounded-lg transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    Showing {{ $doctors->firstItem() ?? 0 }} to {{ $doctors->lastItem() ?? 0 }} of {{ $doctors->total() }} doctors
                </div>
            </div>
        </form>
    </div>

    <!-- Doctors Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Doctor List
            </h3>
        </div>
        <div class="overflow-x-auto">
            @if($doctors->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Specialization</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clinic</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Referrals</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pending Commission</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($doctors as $doctor)
                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-violet-100 flex items-center justify-center">
                                            <span class="text-violet-600 font-semibold text-sm">{{ substr($doctor->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $doctor->name }}</div>
                                            @if($doctor->qualification)
                                                <div class="text-xs text-gray-500">{{ $doctor->qualification }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $doctor->specialization ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $doctor->clinic_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $doctor->phone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold text-gray-900 bg-slate-100 rounded-lg">
                                        {{ $doctor->test_orders_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div class="text-gray-900 font-semibold">₹{{ number_format($doctor->pending_commission ?? 0, 2) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $doctor->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('doctors.show', $doctor) }}" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                            title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('doctors.edit', $doctor) }}" 
                                            class="p-2 text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" 
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('doctors.commissions', $doctor) }}" 
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                            title="Commissions">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('doctors.destroy', $doctor) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $doctors->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No doctors found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding a new doctor.</p>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('doctors.index') }}" class="mt-4 inline-block text-violet-600 hover:text-indigo-800 font-medium">Clear filters</a>
                    @else
                        <div class="mt-6">
                            <a href="{{ route('doctors.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-violet-600 hover:bg-violet-700 shadow-lg shadow-violet-500/25">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Doctor
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
