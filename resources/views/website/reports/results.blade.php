@extends('website.layout')

@section('content')
@php
    $reportLabel = function ($report) {
        if (!empty($report->report_name)) {
            return $report->report_name;
        }
        if ($report->reportTests && $report->reportTests->count() > 0) {
            return $report->reportTests->pluck('test.name')->filter()->unique()->implode(', ') ?: 'Laboratory report';
        }
        return $report->testOrder?->test?->name ?? 'Laboratory report';
    };
@endphp

<section class="relative overflow-hidden border-b border-slate-200/80 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="absolute inset-0 opacity-[0.06] bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-white/50 mb-2" aria-label="Steps">
                    <span class="rounded-full px-2.5 py-0.5 bg-white/10 text-white/80">1 · Search</span>
                    <span class="text-white/30">→</span>
                    <span class="rounded-full px-2.5 py-0.5 bg-emerald-500/30 text-emerald-100 ring-1 ring-emerald-400/40">2 · Your reports</span>
                </nav>
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-white tracking-tight">Your reports</h1>
                <p class="mt-2 text-slate-300 text-sm max-w-xl">Download PDFs or email yourself a secure link for any report marked ready.</p>
            </div>
            <a href="{{ route('public.reports.search') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/15 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                New search
            </a>
        </div>
    </div>
    <div class="relative w-full overflow-hidden leading-[0] text-slate-50">
        <svg class="relative block w-full h-[32px] sm:h-[40px]" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M0,96L60,88C120,80,240,64,360,58.7C480,53,600,59,720,64C840,69,960,75,1080,69.3C1200,64,1320,48,1380,40L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"/>
        </svg>
    </div>
</section>

<section class="relative bg-gradient-to-b from-slate-50 to-white -mt-px pb-16 md:pb-24">
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 md:pt-10">

        @if(isset($patient))
            <div class="mb-6 flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200/80 bg-white px-4 py-3 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                <p class="text-sm text-slate-700">
                    Showing reports for <strong class="text-slate-900">{{ Str::title(Str::lower($patient->name)) }}</strong>
                    <span class="tabular-nums text-slate-500">· {{ $patient->phone }}</span>
                </p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm" role="alert">
                <p class="font-medium text-amber-950">No account found for this number</p>
                <p class="mt-1 text-sm text-amber-900/90">{{ session('error') }}</p>
                <p class="mt-3 text-sm text-amber-800">Please check the number and try again, or contact us if you need help.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('public.reports.search') }}" class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white text-sm shadow-sm transition hover:opacity-95" style="background-color: var(--primary-color);">Try again</a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50">Contact us</a>
                </div>
            </div>
        @endif

        @if(isset($reports) && $reports->count() > 0)
            <ul class="space-y-4">
                @foreach($reports as $report)
                    @php
                        $label = $reportLabel($report);
                        $safeLabelJs = addslashes($label);
                    @endphp
                    <li class="rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-200/40 overflow-hidden">
                        <div class="flex flex-col md:flex-row md:items-stretch">
                            <div class="flex-1 p-5 sm:p-6 border-b md:border-b-0 md:border-r border-slate-100">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white shadow-sm" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e293b) 100%);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h2 class="font-display font-semibold text-slate-900 text-base leading-snug">{{ $label }}</h2>
                                        <p class="mt-1 text-sm text-slate-500">
                                            <span class="tabular-nums">{{ $report->created_at->format('M j, Y') }}</span>
                                            @if($report->report_number)
                                                <span class="text-slate-400"> · </span>
                                                <span class="text-slate-600">{{ $report->report_number }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-stretch justify-center gap-2 p-4 sm:p-5 bg-slate-50/80 md:min-w-[min(100%,280px)]">
                                <span class="inline-flex items-center justify-center self-start sm:self-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($report->status) }}
                                </span>
                                <div class="flex flex-wrap gap-2 sm:ml-auto md:ml-0 lg:ml-auto">
                                    @if($report->status === 'ready')
                                        <a href="{{ route('public.reports.download', $report->id) }}" class="inline-flex flex-1 sm:flex-none items-center justify-center gap-2 min-h-[44px] px-4 rounded-xl text-sm font-semibold text-white shadow-lg transition hover:brightness-110" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #1e293b) 100%); box-shadow: 0 4px 12px color-mix(in srgb, var(--primary-color) 30%, transparent);">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Download PDF
                                        </a>
                                        <button type="button" onclick="openEmailModal({{ $report->id }}, '{{ $safeLabelJs }}')" class="inline-flex flex-1 sm:flex-none items-center justify-center gap-2 min-h-[44px] px-4 rounded-xl text-sm font-semibold border-2 border-slate-200 bg-white text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition">
                                            <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            Email link
                                        </button>
                                    @else
                                        <span class="text-sm text-slate-400 self-center">Not ready</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if(session('email_sent'))
                <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 text-sm">{{ session('email_sent') }}</div>
            @endif
        @elseif(!session('error'))
            <div class="text-center py-14 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-slate-900 font-semibold">No reports found</p>
                <p class="mt-1 text-sm text-slate-500 max-w-md mx-auto">Reports appear here once the lab releases them. If you expected a report, please contact us.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('public.reports.search') }}" class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white text-sm shadow-sm" style="background-color: var(--primary-color);">Search again</a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50">Contact us</a>
                </div>
            </div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('public.reports.search') }}" class="inline-flex items-center gap-2 text-sm font-semibold transition-opacity hover:opacity-90" style="color: var(--primary-color);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to search
            </a>
        </div>
    </div>
</section>

@if(isset($reports) && $reports->count() > 0)
<div id="emailReportModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" aria-hidden="true">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-100" @click.stop>
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Email report link</h3>
        <p class="text-sm text-slate-600 mb-4">We’ll send a secure download link to your email.</p>
        <form id="emailReportForm" method="POST" action="{{ route('public.reports.email-link') }}">
            @csrf
            <input type="hidden" name="report_id" id="email_report_id" value="">
            <div class="mb-4">
                <label for="email_report_email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                <input type="email" name="email" id="email_report_email" required placeholder="you@example.com"
                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:ring-2 focus:ring-offset-0 px-3 py-2.5 text-sm"
                    style="--tw-ring-color: var(--primary-color);">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEmailModal()" class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl text-slate-700 font-semibold text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-white font-semibold text-sm shadow-sm" style="background-color: var(--primary-color);">Send link</button>
            </div>
        </form>
    </div>
</div>
<script>
function openEmailModal(reportId, reportName) {
    document.getElementById('email_report_id').value = reportId;
    document.getElementById('email_report_email').value = '';
    document.getElementById('emailReportModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEmailModal() {
    document.getElementById('emailReportModal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.getElementById('emailReportModal').addEventListener('click', function(e) { if (e.target === this) closeEmailModal(); });
</script>
@endif
@endsection
