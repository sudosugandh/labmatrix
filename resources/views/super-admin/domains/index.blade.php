@extends('super-admin.layout')

@section('title', 'Domains — Super Admin')
@section('page-title', 'Domain management')
@section('page-subtitle', 'All custom domains mapped to diagnostic centers.')

@section('content')
<div class="space-y-6">
    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm sm:p-5">
        <form method="GET" action="{{ route('super-admin.domains.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px] flex-1">
                <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Domain or lab name…"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
            </div>
            <div class="min-w-[140px]">
                <label for="active" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                <select name="active" id="active" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <option value="">All</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="sa-icon-btn border border-violet-600 bg-violet-600 text-white shadow-sm hover:bg-violet-700" title="Apply filters" aria-label="Apply filters">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </button>
                @if(request()->hasAny(['search', 'active']))
                    <a href="{{ route('super-admin.domains.index') }}" class="sa-icon-btn border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100" title="Clear filters" aria-label="Clear filters">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="sa-display text-base font-semibold text-slate-900">All domains <span class="font-normal text-slate-500">({{ $domains->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            @if($domains->count() > 0)
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-6 py-3">Domain</th>
                            <th class="px-6 py-3">Lab</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($domains as $domain)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-3.5">
                                    <span class="font-mono text-sm font-medium text-slate-900">{{ $domain->domain }}</span>
                                </td>
                                <td class="px-6 py-3.5 text-slate-700">
                                    @if($domain->lab)
                                        {{ $domain->lab->name }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    @if($domain->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Active</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    @if($domain->lab)
                                        <a href="{{ route('super-admin.labs.show', $domain->lab) }}" class="sa-icon-btn border border-slate-200 bg-white text-violet-600 shadow-sm hover:border-violet-200 hover:bg-violet-50" title="Open lab" aria-label="Open lab">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $domains->links() }}
                </div>
            @else
                <div class="px-6 py-16 text-center text-slate-500">
                    <p class="text-sm font-medium">No domains match your filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
