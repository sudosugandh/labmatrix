<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Patients</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage your patient records</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-export-button :route="route('export.patients')" />
                <a href="{{ route('patients.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="hidden sm:inline">Add Patient</span>
                    <span class="sm:hidden">Add</span>
                </a>
            </div>
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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Patients', 'value' => $stats['total'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'bg' => 'bg-violet-100', 'text' => 'text-violet-600'],
            ['label' => 'Male', 'value' => $stats['male'], 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'bg' => 'bg-sky-100', 'text' => 'text-sky-600'],
            ['label' => 'Female', 'value' => $stats['female'], 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'bg' => 'bg-pink-100', 'text' => 'text-pink-600'],
            ['label' => 'Added Today', 'value' => $stats['today'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
        ] as $stat)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stat['value']) }}</p>
                    </div>
                    <div class="p-3 {{ $stat['bg'] }} rounded-xl">
                        <svg class="w-6 h-6 {{ $stat['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path></svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('patients.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, phone, or email..." class="block w-full pl-10 pr-3 py-2.5 rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
                    </div>
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-slate-700 mb-2">Gender</label>
                    <select name="gender" id="gender" class="block w-full py-2.5 rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
                        <option value="">All</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label for="age_min" class="block text-sm font-medium text-slate-700 mb-2">Age Range</label>
                    <div class="flex gap-2">
                        <input type="number" name="age_min" id="age_min" value="{{ request('age_min') }}" placeholder="Min" min="0" max="150" class="block w-full py-2.5 rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
                        <input type="number" name="age_max" id="age_max" value="{{ request('age_max') }}" placeholder="Max" min="0" max="150" class="block w-full py-2.5 rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-medium shadow-lg shadow-violet-500/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'gender', 'age_min', 'age_max']))
                        <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Clear</a>
                    @endif
                </div>
                <div class="text-sm text-slate-500">
                    Showing {{ $patients->firstItem() ?? 0 }} to {{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} patients
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30">
            <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Patient List
            </h3>
        </div>
        <div class="overflow-x-auto">
            @if($patients->count() > 0)
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                <a href="{{ route('patients.index', array_merge(request()->all(), ['sort_by' => 'name', 'sort_order' => request('sort_by') === 'name' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-violet-600 transition-colors">
                                    <span>Patient</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Registered</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach($patients as $patient)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-violet-100 flex items-center justify-center">
                                            <span class="text-violet-600 font-semibold text-sm">{{ substr($patient->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-slate-900">{{ $patient->name }}</div>
                                            @if($patient->email)
                                                <div class="text-xs text-slate-500">{{ $patient->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $patient->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-700">{{ $patient->age ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($patient->gender)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $patient->gender === 'male' ? 'bg-sky-100 text-sky-800' : ($patient->gender === 'female' ? 'bg-pink-100 text-pink-800' : 'bg-slate-100 text-slate-800') }}">
                                            {{ ucfirst($patient->gender) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-500">{{ $patient->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-slate-400">{{ $patient->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('patients.show', $patient) }}" 
                                            class="p-2.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors" 
                                            title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient) }}" 
                                            class="p-2.5 text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" 
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this patient? This action cannot be undone.')">
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
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/80">
                    {{ $patients->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="mt-4 text-slate-500 text-lg">No patients found.</p>
                    @if(request()->hasAny(['search', 'gender', 'age_min', 'age_max']))
                        <p class="mt-2 text-sm text-slate-400">Try adjusting your search filters.</p>
                        <a href="{{ route('patients.index') }}" class="mt-4 inline-block text-violet-600 hover:text-violet-700 font-medium">Clear filters</a>
                    @else
                        <a href="{{ route('patients.create') }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold shadow-lg shadow-violet-500/25 hover:shadow-xl transition-all">Create your first patient</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
