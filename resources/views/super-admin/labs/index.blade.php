@extends('super-admin.layout')

@section('title', 'Labs — Super Admin')
@section('page-title', 'Labs')
@section('page-subtitle', 'View and manage all diagnostic centers.')

@section('content')
@php
    $input = 'w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20';
@endphp
<div class="space-y-6">
    <div class="flex flex-wrap justify-end gap-2">
            <a href="{{ route('super-admin.labs.export') }}" class="sa-icon-btn border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50" title="Export labs CSV" aria-label="Export labs CSV">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </a>
            <a href="{{ route('super-admin.labs.create') }}" class="sa-icon-btn border border-violet-600 bg-violet-600 text-white shadow-sm hover:bg-violet-700" title="Create new lab" aria-label="Create new lab">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm sm:p-5">
        <form method="GET" action="{{ route('super-admin.labs.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px] flex-1">
                <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, email, phone…" class="{{ $input }}">
            </div>
            <div class="min-w-[130px]">
                <label for="level" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Level</label>
                <select name="level" id="level" class="{{ $input }}">
                    <option value="">All</option>
                    <option value="small" {{ request('level') === 'small' ? 'selected' : '' }}>Small</option>
                    <option value="large" {{ request('level') === 'large' ? 'selected' : '' }}>Large</option>
                </select>
            </div>
            <div class="min-w-[130px]">
                <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Lab status</label>
                <select name="status" id="status" class="{{ $input }}">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="min-w-[130px]">
                <label for="subscription_plan" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Plan</label>
                <select name="subscription_plan" id="subscription_plan" class="{{ $input }}">
                    <option value="">All plans</option>
                    <option value="trial" {{ request('subscription_plan') === 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="basic" {{ request('subscription_plan') === 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="pro" {{ request('subscription_plan') === 'pro' ? 'selected' : '' }}>Pro</option>
                    <option value="enterprise" {{ request('subscription_plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="subscription_status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Billing</label>
                <select name="subscription_status" id="subscription_status" class="{{ $input }}">
                    <option value="">All</option>
                    <option value="active" {{ request('subscription_status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('subscription_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="suspended" {{ request('subscription_status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="cancelled" {{ request('subscription_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="sa-icon-btn border border-violet-600 bg-violet-600 text-white shadow-sm hover:bg-violet-700" title="Apply filters" aria-label="Apply filters">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </button>
                @if(request()->hasAny(['search', 'level', 'status', 'subscription_plan', 'subscription_status']))
                    <a href="{{ route('super-admin.labs.index') }}" class="sa-icon-btn border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100" title="Clear filters" aria-label="Clear filters">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="sa-display text-base font-semibold text-slate-900">All diagnostic centers <span class="font-normal text-slate-500">({{ $labs->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            @if($labs->count() > 0)
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-6 py-3">Lab</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Level</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Plan</th>
                            <th class="px-6 py-3">Billing</th>
                            <th class="px-6 py-3">Added</th>
                            <th class="px-6 py-3">Last order</th>
                            <th class="px-6 py-3 text-right tabular-nums">Users</th>
                            <th class="px-6 py-3 text-right tabular-nums">Patients</th>
                            <th class="px-6 py-3 text-right tabular-nums">Orders</th>
                            <th class="px-6 py-3 text-right tabular-nums">Domains</th>
                            <th class="px-6 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($labs as $lab)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-3.5 font-semibold text-slate-900 whitespace-nowrap">{{ $lab->name }}</td>
                                <td class="px-6 py-3.5 text-slate-600 whitespace-nowrap">{{ $lab->email }}</td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $lab->level === 'large' ? 'bg-violet-100 text-violet-800' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($lab->level) }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ ($lab->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($lab->status ?? 'active') }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    @php
                                        $plan = $lab->subscription_plan ?? 'trial';
                                        $planClass = match ($plan) {
                                            'trial' => 'bg-amber-100 text-amber-900',
                                            'basic' => 'bg-sky-100 text-sky-900',
                                            'pro' => 'bg-violet-100 text-violet-900',
                                            'enterprise' => 'bg-fuchsia-100 text-fuchsia-900',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $planClass }}">{{ ucfirst($plan) }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    @php
                                        $sub = $lab->subscription_status ?? 'active';
                                        $subClass = match ($sub) {
                                            'active' => 'bg-emerald-100 text-emerald-900',
                                            'expired' => 'bg-red-100 text-red-900',
                                            'suspended' => 'bg-orange-100 text-orange-900',
                                            'cancelled' => 'bg-slate-200 text-slate-800',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $subClass }}">{{ ucfirst($sub) }}</span>
                                </td>
                                <td class="px-6 py-3.5 text-slate-500 tabular-nums whitespace-nowrap" title="{{ $lab->created_at->toIso8601String() }}">{{ $lab->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-3.5 text-slate-600 whitespace-nowrap">
                                    @if($lab->test_orders_max_created_at)
                                        <span title="{{ \Illuminate\Support\Carbon::parse($lab->test_orders_max_created_at)->format('Y-m-d H:i') }}">{{ \Illuminate\Support\Carbon::parse($lab->test_orders_max_created_at)->diffForHumans() }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-right tabular-nums text-slate-600">{{ $lab->users_count }}</td>
                                <td class="px-6 py-3.5 text-right tabular-nums text-slate-600">{{ $lab->patients_count }}</td>
                                <td class="px-6 py-3.5 text-right tabular-nums text-slate-600">{{ $lab->test_orders_count ?? 0 }}</td>
                                <td class="px-6 py-3.5 text-right tabular-nums text-slate-600">{{ $lab->domains_count }}</td>
                                <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1">
                                        <a href="{{ route('super-admin.labs.show', $lab) }}" class="sa-icon-btn border border-slate-200 bg-white text-violet-600 shadow-sm hover:border-violet-200 hover:bg-violet-50" title="View lab" aria-label="View lab">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('super-admin.labs.edit', $lab) }}" class="sa-icon-btn border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50" title="Edit lab" aria-label="Edit lab">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $labs->links() }}
                </div>
            @else
                <div class="px-6 py-16 text-center text-slate-500">
                    <p class="text-sm font-medium">No labs match your filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
