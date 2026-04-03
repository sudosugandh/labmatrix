@extends('website.layout')

@section('content')
@include('website.partials.doctor-portal-chrome', [
    'title' => 'Welcome, '.$doctor->name,
    'subtitle' => 'Track referrals, commissions, and released reports for patients who booked through your link.',
    'active' => 'overview',
])

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-500">Pending commission</p>
                <p class="text-xl font-bold tabular-nums text-slate-900 tracking-tight">₹{{ number_format($stats['pending_commission'] ?? 0, 2) }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-500">Referrals</p>
                <p class="text-xl font-bold tabular-nums text-slate-900 tracking-tight">{{ number_format($stats['referrals_count'] ?? 0) }} <span class="text-sm font-medium text-slate-500">orders</span></p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm ring-1 ring-slate-900/5 sm:col-span-1">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-500">Reports ready</p>
                <p class="text-xl font-bold tabular-nums text-slate-900 tracking-tight">{{ number_format($stats['reports_ready'] ?? 0) }} <span class="text-sm font-medium text-slate-500">released</span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
        <a href="{{ route('doctor.portal.referrals') }}" class="group flex items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-4 py-3.5 text-sm font-semibold text-slate-800 shadow-sm ring-1 ring-slate-900/5 transition hover:border-[color-mix(in_srgb,var(--primary-color)_35%,transparent)] hover:ring-[color-mix(in_srgb,var(--primary-color)_12%,transparent)]">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600 group-hover:bg-sky-100" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </span>
            <span class="min-w-0">View all referrals <span class="block text-xs font-normal text-slate-500">Orders linked to you</span></span>
            <svg class="ml-auto w-4 h-4 shrink-0 text-slate-400 group-hover:text-[color:var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
        <a href="{{ route('doctor.portal.commissions') }}" class="group flex items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-4 py-3.5 text-sm font-semibold text-slate-800 shadow-sm ring-1 ring-slate-900/5 transition hover:border-[color-mix(in_srgb,var(--primary-color)_35%,transparent)] hover:ring-[color-mix(in_srgb,var(--primary-color)_12%,transparent)]">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600 group-hover:bg-amber-100" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
            </span>
            <span class="min-w-0">Commission history <span class="block text-xs font-normal text-slate-500">Pending &amp; paid</span></span>
            <svg class="ml-auto w-4 h-4 shrink-0 text-slate-400 group-hover:text-[color:var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
        <a href="{{ route('doctor.portal.reports') }}" class="group flex items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-4 py-3.5 text-sm font-semibold text-slate-800 shadow-sm ring-1 ring-slate-900/5 transition hover:border-[color-mix(in_srgb,var(--primary-color)_35%,transparent)] hover:ring-[color-mix(in_srgb,var(--primary-color)_12%,transparent)]">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </span>
            <span class="min-w-0">Download reports <span class="block text-xs font-normal text-slate-500">Released PDFs</span></span>
            <svg class="ml-auto w-4 h-4 shrink-0 text-slate-400 group-hover:text-[color:var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200/90 border-l-[3px] border-l-[color:var(--primary-color)] bg-gradient-to-br from-white to-slate-50/80 p-5 sm:p-6 shadow-sm ring-1 ring-slate-900/5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Your referral link</h2>
                <p class="text-sm text-slate-600 mt-1">Patients who book through this link are counted as your referrals. Use the lab’s public site URL (not <code class="text-xs bg-slate-100 px-1 rounded">/login</code>).</p>
            </div>
        </div>
        <div class="flex flex-col gap-3" x-data="{ copied: false }">
            <label class="sr-only" for="referral-url">Referral URL</label>
            <div class="flex flex-col sm:flex-row gap-2 min-w-0">
                <input id="referral-url" type="text" readonly value="{{ $doctor->referralBookingUrl() }}"
                    class="min-w-0 w-full flex-1 rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2.5 text-xs sm:text-sm font-mono text-slate-800 shadow-inner focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-color)] focus:ring-offset-0"
                />
                <button type="button"
                    @click="navigator.clipboard.writeText(@js($doctor->referralBookingUrl())); copied = true; clearTimeout(window._refCopyT); window._refCopyT = setTimeout(() => copied = false, 2000)"
                    class="shrink-0 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[color:var(--primary-color)]"
                    style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 88%, #1e293b) 100%);">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span x-show="!copied" x-cloak>Copy link</span>
                    <span x-show="copied" x-cloak class="text-white">Copied!</span>
                </button>
            </div>
            <p class="text-xs text-slate-500">
                Referral code:
                <span class="font-mono font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">{{ $doctor->referral_code }}</span>
            </p>
        </div>
    </div>
</div>
@endsection
