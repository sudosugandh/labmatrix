@extends('super-admin.layout')

@section('title', 'Manage Labs - HealthMatrix Super Admin')
@section('page-title', 'Manage Labs')
@section('page-subtitle', 'View and manage all diagnostic centers')

@section('content')
<div class="space-y-6">
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">All Labs</h2>
            <p class="text-sm text-gray-500 mt-1">Manage all diagnostic centers</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('super-admin.labs.export') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </a>
            <a href="{{ route('super-admin.labs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Lab
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('super-admin.labs.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    placeholder="Search by name, email, phone..."
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
            </div>
            <div class="min-w-[150px]">
                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                <select name="level" id="level" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    <option value="">All Levels</option>
                    <option value="small" {{ request('level') === 'small' ? 'selected' : '' }}>Small</option>
                    <option value="large" {{ request('level') === 'large' ? 'selected' : '' }}>Large</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-sm hover:shadow-md transition-all">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'level', 'status']))
                    <a href="{{ route('super-admin.labs.index') }}" class="ml-2 text-gray-600 hover:text-gray-900 font-medium">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
            <h3 class="text-lg font-semibold text-white">All Diagnostic Centers ({{ $labs->total() }})</h3>
        </div>
        <div class="overflow-x-auto">
            @if($labs->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lab Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patients</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Domains</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($labs as $lab)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $lab->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lab->level === 'large' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($lab->level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($lab->status ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($lab->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->users_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->patients_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->test_orders_count ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lab->domains_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('super-admin.labs.show', $lab) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                                        <a href="{{ route('super-admin.labs.edit', $lab) }}" class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $labs->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <p class="mt-4 text-gray-500 text-lg">No labs found.</p>
                </div>
            @endif
    </div>
</div>
@endsection
