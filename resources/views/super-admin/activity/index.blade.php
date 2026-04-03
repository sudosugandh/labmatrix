@extends('super-admin.layout')

@section('title', 'Activity — Super Admin')
@section('page-title', 'Activity log')
@section('page-subtitle', 'Super-admin actions: labs, domains, users, exports, and more.')

@section('content')
<div class="space-y-6">
    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm sm:p-5">
        <form method="GET" action="{{ route('super-admin.activity.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px] flex-1">
                <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Description or action…"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
            </div>
            <div class="min-w-[220px]">
                <label for="action" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Action</label>
                <select name="action" id="action" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <option value="">All actions</option>
                    @foreach($actionChoices as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="sa-icon-btn border border-violet-600 bg-violet-600 text-white shadow-sm hover:bg-violet-700" title="Apply filters" aria-label="Apply filters">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </button>
                @if(request()->hasAny(['search', 'action']))
                    <a href="{{ route('super-admin.activity.index') }}" class="sa-icon-btn border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100" title="Clear filters" aria-label="Clear filters">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="sa-display text-base font-semibold text-slate-900">Events <span class="font-normal text-slate-500">({{ $logs->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            @if($logs->count() > 0)
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-6 py-3">When</th>
                            <th class="px-6 py-3">Actor</th>
                            <th class="px-6 py-3">Action</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3">IP</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($logs as $log)
                            <tr class="align-top transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-3.5 text-slate-600 tabular-nums whitespace-nowrap">
                                    <span title="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->format('M j, Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="font-medium text-slate-900">{{ $log->user?->name ?? '—' }}</div>
                                    <div class="text-xs text-slate-500">{{ $log->user?->email }}</div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <code class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-violet-800">{{ $log->action }}</code>
                                </td>
                                <td class="max-w-md px-6 py-3.5 text-slate-700">{{ $log->description ?? '—' }}</td>
                                <td class="px-6 py-3.5 font-mono text-xs text-slate-500 whitespace-nowrap">{{ $log->ip_address ?? '—' }}</td>
                                <td class="px-6 py-3.5">
                                    @if($log->metadata && count($log->metadata) > 0)
                                        <details class="text-xs">
                                            <summary class="cursor-pointer font-semibold text-violet-600 hover:text-violet-800">Details</summary>
                                            <pre class="mt-2 max-h-40 overflow-auto rounded-lg bg-slate-900 p-2 text-[11px] text-slate-100">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </details>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="px-6 py-16 text-center text-slate-500">
                    <p class="text-sm font-medium">No activity recorded yet, or nothing matches your filters.</p>
                    <p class="mt-1 text-xs">Actions are logged when you change labs, domains, users, subscriptions, or run exports.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
