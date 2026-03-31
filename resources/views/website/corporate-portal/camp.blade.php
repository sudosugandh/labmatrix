@extends('website.layout')

@section('content')
@php
    $campSubtitle = collect([
        $healthCamp->venue,
        $healthCamp->camp_date?->format('l, F j, Y'),
        ucfirst($healthCamp->status ?? ''),
    ])->filter()->implode(' · ');
@endphp
@include('website.partials.corporate-portal-chrome', [
    'title' => $healthCamp->name,
    'subtitle' => $campSubtitle ?: 'Camp details and participants',
    'active' => 'camp',
    'back' => ['url' => route('corporate.portal.dashboard'), 'label' => 'All camps'],
])

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200/90 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Unique patients</p>
                <p class="text-xl font-bold tabular-nums text-slate-900">{{ $uniquePatients }}</p>
            </div>
        </div>
        <a href="{{ route('corporate.portal.camp.export', $healthCamp) }}" class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[color:var(--primary-color)]" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 88%, #1e293b) 100%);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export CSV
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200/90 bg-white shadow-sm ring-1 ring-slate-900/5 overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-200/90">
            <h2 class="text-lg font-semibold text-slate-900">Participants</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Patient</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Phone</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Test</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Order status</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Report</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-slate-900">{{ $order->patient?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-600 tabular-nums">{{ $order->patient?->phone ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-700">{{ $order->test?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-600 capitalize">{{ str_replace('_', ' ', $order->report_status ?? '—') }}</td>
                            <td class="px-4 py-3.5 text-sm">
                                @if($order->report && $order->report->workflow_status === 'released' && $order->report->status === 'ready')
                                    <a href="{{ route('public.reports.download', $order->report->id) }}" class="font-semibold text-[color:var(--primary-color)] hover:underline">Download</a>
                                @else
                                    <span class="text-slate-400">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-14 text-center text-slate-500 text-sm">No orders linked to this camp yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
