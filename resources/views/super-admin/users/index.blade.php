@extends('super-admin.layout')

@section('title', 'Users — Super Admin')
@section('page-title', 'User management')
@section('page-subtitle', 'Accounts across all labs. Super admins are hidden unless you include them.')

@section('content')
@php
    $roleOptions = ['admin' => 'Admin', 'staff' => 'Staff', 'user' => 'User', 'super_admin' => 'Super admin'];
@endphp
<div class="space-y-6">
    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm sm:p-5">
        <form method="GET" action="{{ route('super-admin.users.index') }}" class="space-y-4">
            <div class="flex flex-wrap items-end gap-4">
                <div class="min-w-[200px] flex-1">
                    <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Name or email</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Search…"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                </div>
                <div class="min-w-[160px]">
                    <label for="role" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Role</label>
                    <select name="role" id="role" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                        <option value="">All roles</option>
                        @foreach($roleOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[180px] flex-1">
                    <label for="lab_search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Lab name</label>
                    <input type="text" name="lab_search" id="lab_search" value="{{ request('lab_search') }}"
                        placeholder="Filter by lab…"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-4">
                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="include_super" value="1" class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500" {{ request()->boolean('include_super') ? 'checked' : '' }}>
                    Include super admins
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="sa-icon-btn border border-violet-600 bg-violet-600 text-white shadow-sm hover:bg-violet-700" title="Apply filters" aria-label="Apply filters">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    </button>
                    @if(request()->hasAny(['search', 'role', 'lab_search', 'include_super']))
                        <a href="{{ route('super-admin.users.index') }}" class="sa-icon-btn border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100" title="Clear filters" aria-label="Clear filters">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="sa-display text-base font-semibold text-slate-900">All users <span class="font-normal text-slate-500">({{ $users->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            @if($users->count() > 0)
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Lab</th>
                            <th class="px-6 py-3">Joined</th>
                            <th class="px-6 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-3.5">
                                    <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-3.5">
                                    @php
                                        $rc = match ($user->role) {
                                            'super_admin' => 'bg-violet-100 text-violet-800',
                                            'admin' => 'bg-blue-100 text-blue-800',
                                            'staff' => 'bg-amber-100 text-amber-900',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $rc }}">{{ str_replace('_', ' ', ucfirst($user->role)) }}</span>
                                </td>
                                <td class="px-6 py-3.5 text-slate-700">
                                    @if($user->lab)
                                        {{ $user->lab->name }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-slate-500 tabular-nums">{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-3.5 text-right">
                                    @if($user->lab)
                                        <a href="{{ route('super-admin.labs.show', $user->lab) }}" class="sa-icon-btn border border-slate-200 bg-white text-violet-600 shadow-sm hover:border-violet-200 hover:bg-violet-50" title="Open lab" aria-label="Open lab">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="px-6 py-16 text-center text-slate-500">
                    <p class="text-sm font-medium">No users match your filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
