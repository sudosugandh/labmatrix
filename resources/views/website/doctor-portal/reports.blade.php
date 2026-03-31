@extends('website.layout')

@section('content')
@include('website.partials.doctor-portal-chrome', [
    'title' => 'Reports',
    'subtitle' => 'Released PDFs for your referred orders. Status reflects the lab’s order, visit, and report progress.',
    'active' => 'reports',
])

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="rounded-2xl border border-slate-200/90 bg-white shadow-sm ring-1 ring-slate-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Report</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Patient</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Test</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide" title="Same as Referrals — from lab updates">Status</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-slate-900">{{ $report->report_name ?? $report->report_number ?? 'Report #'.$report->id }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-800">{{ $report->patient?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-700">{{ $report->testOrder?->test?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-700 max-w-sm">
                                @if($report->testOrder)
                                    <span class="inline-flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/15">Ready</span>
                                        <span class="text-slate-600">{{ $report->testOrder->doctorPortalStatusLabel() }}</span>
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('public.reports.download', $report->id) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-[color:var(--primary-color)] hover:underline">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-14 text-center text-slate-500 text-sm">No released reports yet for your referrals.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $reports->links() }}</div>
        @endif
    </div>
</div>
@endsection
