@extends('super-admin.layout')

@section('title', 'Super Admin Dashboard - HealthMatrix')
@section('page-title', 'Super Admin Dashboard')
@section('page-subtitle', 'Monitor all diagnostic centers and lab activity from one place.')

@section('content')
<div class="space-y-8">
    <div class="rounded-2xl border border-violet-200 bg-violet-50/90 p-5 text-sm text-slate-800 shadow-sm">
        <p class="font-semibold text-slate-900">Platform console (Super Admin)</p>
        <p class="mt-2 leading-relaxed text-slate-700">
            You manage <strong>tenants</strong> (labs), domains, subscription and commercial fields, plan limits, and high‑risk overrides. Each lab’s team uses the normal <strong>Lab admin</strong> app for patients, tests, orders, reports, billing to patients, and day‑to‑day website/CMS content. Use this area for platform health, not for routine lab content editing.
        </p>
    </div>

    {{-- Primary KPI row (reference-style: value left, icon right) --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-500">Total labs</p>
                    <p class="sa-display mt-2 text-3xl font-bold tabular-nums tracking-tight text-slate-900">{{ number_format($stats['total_labs']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $stats['active_labs'] }} active · {{ $stats['suspended_labs'] }} suspended</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-500 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-500">Total users</p>
                    <p class="sa-display mt-2 text-3xl font-bold tabular-nums tracking-tight text-slate-900">{{ number_format($stats['total_users']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Across all labs</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm shadow-emerald-500/25">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-500">Total patients</p>
                    <p class="sa-display mt-2 text-3xl font-bold tabular-nums tracking-tight text-slate-900">{{ number_format($stats['total_patients']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Registered patients</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-500 text-white shadow-sm shadow-violet-500/25">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary metrics --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Orders</p>
                    <p class="sa-display mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ number_format($stats['total_orders']) }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">Test orders</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Domains</p>
                    <p class="sa-display mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ number_format($stats['total_domains']) }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">{{ $stats['active_domains'] }} active</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Reports</p>
                    <p class="sa-display mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ number_format($stats['total_reports']) }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">Generated</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm ring-1 ring-emerald-500/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Active labs</p>
                    <p class="sa-display mt-1 text-2xl font-bold tabular-nums text-emerald-600">{{ number_format($stats['active_labs']) }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">Operational</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm sm:col-span-2 lg:col-span-1 xl:col-span-1">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Suspended</p>
                    <p class="sa-display mt-1 text-2xl font-bold tabular-nums text-red-600">{{ number_format($stats['suspended_labs']) }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">Disabled</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="sa-display text-base font-semibold text-slate-900">Platform growth trends</h3>
            <p class="mt-0.5 text-xs text-slate-500">Last 12 months — orders, reports, new patients, and new labs.</p>
        </div>
        <div class="p-4 sm:p-6">
            <div class="relative h-[280px] w-full sm:h-[320px]">
                <canvas id="saPlatformGrowthChart"></canvas>
            </div>
        </div>
        <script type="application/json" id="saPlatformGrowthChartConfig">@json($growthChartConfig)</script>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="sa-display text-base font-semibold text-slate-900">Lab levels distribution</h3>
                <p class="mt-0.5 text-xs text-slate-500">By subscription tier</p>
            </div>
            <div class="divide-y divide-slate-100 p-2">
                <div class="flex items-center justify-between rounded-lg px-4 py-3 hover:bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-violet-500"></span>
                        <span class="text-sm font-medium text-slate-700">Large labs</span>
                    </div>
                    <span class="sa-display text-lg font-bold tabular-nums text-slate-900">{{ $stats['large_labs'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg px-4 py-3 hover:bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-slate-300"></span>
                        <span class="text-sm font-medium text-slate-700">Small labs</span>
                    </div>
                    <span class="sa-display text-lg font-bold tabular-nums text-slate-900">{{ $stats['small_labs'] }}</span>
                </div>
            </div>
            <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-slate-500">Total</span>
                    <span class="sa-display font-bold tabular-nums text-slate-900">{{ $stats['total_labs'] }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="sa-display text-base font-semibold text-slate-900">Lab status distribution</h3>
                <p class="mt-0.5 text-xs text-slate-500">Active vs suspended</p>
            </div>
            <div class="divide-y divide-slate-100 p-2">
                <div class="flex items-center justify-between rounded-lg px-4 py-3 hover:bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                        <span class="text-sm font-medium text-slate-700">Active labs</span>
                    </div>
                    <span class="sa-display text-lg font-bold tabular-nums text-emerald-600">{{ $stats['active_labs'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg px-4 py-3 hover:bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-red-500"></span>
                        <span class="text-sm font-medium text-slate-700">Suspended labs</span>
                    </div>
                    <span class="sa-display text-lg font-bold tabular-nums text-red-600">{{ $stats['suspended_labs'] }}</span>
                </div>
            </div>
            <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-slate-500">Total</span>
                    <span class="sa-display font-bold tabular-nums text-slate-900">{{ $stats['total_labs'] }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(isset($topLabs) && $topLabs->count() > 0)
    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="sa-display text-base font-semibold text-slate-900">Top labs by activity</h3>
                <p class="text-xs text-slate-500">Orders and patient volume</p>
            </div>
            <a href="{{ route('super-admin.labs.index') }}" class="sa-icon-btn border border-violet-200 bg-violet-50 text-violet-700 hover:bg-violet-100" title="View all labs" aria-label="View all labs">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
        <ul class="divide-y divide-slate-100">
            @foreach($topLabs as $index => $lab)
                <li class="flex flex-col gap-3 px-6 py-4 transition-colors hover:bg-slate-50/80 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4 min-w-0">
                        <span class="sa-display flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-100 text-sm font-bold text-violet-700">{{ $index + 1 }}</span>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 truncate">{{ $lab->name }}</p>
                            <p class="text-sm text-slate-500">{{ $lab->test_orders_count }} orders · {{ $lab->patients_count }} patients</p>
                        </div>
                    </div>
                    <a href="{{ route('super-admin.labs.show', $lab) }}" class="sa-icon-btn shrink-0 border border-violet-200 bg-violet-50 text-violet-700 hover:bg-violet-100" title="Open lab" aria-label="Open lab">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="sa-display text-base font-semibold text-slate-900">Quick actions</h3>
        </div>
        <div class="flex flex-wrap gap-3 p-6">
            <a href="{{ route('super-admin.labs.index') }}" class="sa-icon-btn sa-icon-btn-lg border-2 border-violet-200 bg-violet-50/80 text-violet-800 hover:border-violet-300 hover:bg-violet-100" title="Manage labs" aria-label="Manage labs">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </a>
            <a href="{{ route('super-admin.labs.create') }}" class="sa-icon-btn sa-icon-btn-lg border-2 border-emerald-200 bg-emerald-50/80 text-emerald-800 hover:border-emerald-300 hover:bg-emerald-100" title="New lab" aria-label="New lab">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </a>
            <a href="{{ route('super-admin.labs.export') }}" class="sa-icon-btn sa-icon-btn-lg border-2 border-slate-200 bg-slate-50 text-slate-800 hover:border-slate-300 hover:bg-slate-100" title="Export labs data" aria-label="Export labs data">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="sa-display text-base font-semibold text-slate-900">Recent labs</h3>
            <p class="mt-0.5 text-xs text-slate-500">Newest diagnostic centers</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-6 py-3">Lab</th>
                        <th class="px-6 py-3">Level</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Users</th>
                        <th class="px-6 py-3 text-right">Orders</th>
                        <th class="px-6 py-3 text-right">Domains</th>
                        <th class="px-6 py-3">Created</th>
                        <th class="px-6 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentLabs as $lab)
                        <tr class="transition-colors hover:bg-slate-50/60">
                            <td class="px-6 py-3.5 font-semibold text-slate-900">{{ $lab->name }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $lab->level === 'large' ? 'bg-violet-100 text-violet-800' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($lab->level) }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ ($lab->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($lab->status ?? 'active') }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-right tabular-nums text-slate-600">{{ $lab->users_count }}</td>
                            <td class="px-6 py-3.5 text-right tabular-nums text-slate-600">{{ $lab->test_orders_count ?? 0 }}</td>
                            <td class="px-6 py-3.5 text-right tabular-nums text-slate-600">{{ $lab->domains_count }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ $lab->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('super-admin.labs.show', $lab) }}" class="sa-icon-btn inline-flex border border-slate-200 bg-white text-violet-600 shadow-sm hover:border-violet-200 hover:bg-violet-50" title="View lab" aria-label="View lab">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">No labs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
