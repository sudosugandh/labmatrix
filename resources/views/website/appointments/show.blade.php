@extends('website.layout')

@section('content')
@php
    $ref = str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
    $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
    $timeFormatted = \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
    $patientDisplayName = Str::title(Str::lower($appointment->patient->name ?? ''));
    $doctorDisplayName = $appointment->referring_doctor_name
        ? preg_replace('/^dr\.?\s+/i', '', trim($appointment->referring_doctor_name))
        : null;
    $doctorDisplayName = $doctorDisplayName ? 'Dr. ' . Str::title(Str::lower($doctorDisplayName)) : null;
    $heroStatusClass = match($appointment->status) {
        'scheduled' => 'bg-blue-500/25 text-blue-100 ring-blue-400/40',
        'confirmed' => 'bg-emerald-500/25 text-emerald-100 ring-emerald-400/35',
        'completed' => 'bg-slate-500/30 text-slate-100 ring-slate-400/30',
        'cancelled' => 'bg-red-500/25 text-red-100 ring-red-400/35',
        'no_show' => 'bg-amber-500/25 text-amber-100 ring-amber-400/35',
        default => 'bg-slate-500/30 text-slate-100 ring-slate-400/30',
    };
@endphp

{{-- Page hero: identity + reference + actions --}}
<section class="relative overflow-hidden border-b border-slate-200/80 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="absolute inset-0 opacity-[0.07] bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none" style="background: color-mix(in srgb, var(--primary-color) 50%, transparent);"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-white/45 mb-2">My appointments</p>
                <h1 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-white tracking-tight">Appointment details</h1>
                <p class="mt-2 text-slate-300 text-sm sm:text-base max-w-xl">Reference <span class="font-semibold text-white tabular-nums">#{{ $ref }}</span> — keep this ID for your records.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                <span class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-semibold ring-1 backdrop-blur-sm {{ $heroStatusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                </span>
                <a href="{{ route('public.appointments.confirmation.download', $appointment) }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition hover:brightness-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900"
                    style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e293b) 100%); box-shadow: 0 4px 14px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download confirmation
                </a>
            </div>
        </div>
    </div>

    <div class="relative w-full overflow-hidden leading-[0] text-slate-50">
        <svg class="relative block w-full h-[36px] sm:h-[48px]" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M0,96L60,88C120,80,240,64,360,58.7C480,53,600,59,720,64C840,69,960,75,1080,69.3C1200,64,1320,48,1380,40L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"/>
        </svg>
    </div>
</section>

<section class="relative bg-gradient-to-b from-slate-50 to-white -mt-px pb-16 md:pb-24">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-100/40 via-transparent to-transparent pointer-events-none"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 md:pt-10">

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200/80 bg-emerald-50/90 text-emerald-900 px-4 py-3 text-sm shadow-sm" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200/80 bg-red-50 text-red-900 px-4 py-3 text-sm shadow-sm" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xl shadow-slate-200/40 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-0 lg:divide-x divide-slate-100">
                {{-- Main: visit details --}}
                <div class="lg:col-span-3 p-6 sm:p-8">
                    <h2 class="font-display text-lg font-semibold text-slate-900 mb-6 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white shadow-sm" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #334155) 100%);">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Visit information
                    </h2>

                    <dl class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex gap-3 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3">
                                <span class="text-slate-400 shrink-0 mt-0.5" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Date</dt>
                                    <dd class="font-semibold text-slate-900">{{ $appointment->appointment_date->format('l, F j, Y') }}</dd>
                                </div>
                            </div>
                            <div class="flex gap-3 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3">
                                <span class="text-slate-400 shrink-0 mt-0.5" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Time</dt>
                                    <dd class="font-semibold text-slate-900">{{ $timeFormatted }}</dd>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3">
                            <span class="text-xs font-medium uppercase tracking-wide text-slate-500">Status</span>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $appointment->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </div>
                    </dl>

                    @if($appointment->package)
                        <div class="mt-6 rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </span>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Package</p>
                                    <p class="font-semibold text-slate-900">{{ $appointment->package->name }} <span class="font-normal tabular-nums" style="color: var(--primary-color);">(₹{{ number_format($appointment->package->final_price, 2) }})</span></p>
                                    <p class="text-xs text-slate-500 mt-1">{{ $appointment->package->tests->count() }} {{ Str::plural('test', $appointment->package->tests->count()) }} included</p>
                                    @if($appointment->status === 'confirmed' || $appointment->status === 'completed')
                                        <p class="text-xs text-emerald-700 mt-2 font-medium">Test orders are linked to this package when your visit is confirmed.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($appointment->referring_doctor_name || $appointment->prescription_file)
                        <div class="mt-6 rounded-xl border border-sky-200/70 bg-gradient-to-br from-sky-50/90 to-slate-50/40 p-4">
                            <div class="flex items-start gap-2 mb-2">
                                <svg class="h-5 w-5 text-slate-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Referring doctor</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">As provided at booking.</p>
                                </div>
                            </div>
                            @if($doctorDisplayName)
                                <p class="text-sm text-slate-800"><span class="text-slate-500">Name</span> — {{ $doctorDisplayName }}</p>
                            @endif
                            @if($appointment->referring_doctor_phone)
                                <p class="text-sm text-slate-800 mt-1"><span class="text-slate-500">Phone</span> — {{ $appointment->referring_doctor_phone }}</p>
                            @endif
                            @if($appointment->prescription_file)
                                <a href="{{ asset('storage/' . $appointment->prescription_file) }}" target="_blank" rel="noopener noreferrer"
                                    class="mt-3 inline-flex items-center gap-2 text-sm font-semibold transition-opacity hover:opacity-90" style="color: var(--primary-color);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View prescription
                                </a>
                            @endif
                        </div>
                    @endif

                    @if($appointment->notes)
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Your notes</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Sidebar: patient + next steps --}}
                <div class="lg:col-span-2 p-6 sm:p-8 bg-slate-50/60 border-t lg:border-t-0 border-slate-100">
                    <h2 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-200/80 text-slate-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        Patient
                    </h2>

                    <dl class="space-y-3">
                        <div class="rounded-xl border border-slate-200/80 bg-white px-4 py-3 shadow-sm">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Name</dt>
                            <dd class="font-semibold text-slate-900 mt-0.5">{{ $patientDisplayName }}</dd>
                        </div>
                        <div class="rounded-xl border border-slate-200/80 bg-white px-4 py-3 shadow-sm">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Phone</dt>
                            <dd class="font-semibold text-slate-900 tabular-nums mt-0.5">{{ $appointment->patient->phone }}</dd>
                        </div>
                        @if($appointment->patient->email)
                            <div class="rounded-xl border border-slate-200/80 bg-white px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Email</dt>
                                <dd class="font-semibold text-slate-900 text-sm break-all mt-0.5">{{ $appointment->patient->email }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-6 p-4 rounded-xl border border-slate-200/80 bg-white/80">
                        <p class="text-sm font-semibold text-slate-900">Lab reports</p>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">When your tests are complete, reports are released by the lab. You can then retrieve them from <strong>Reports</strong> on this site.</p>
                        <a href="{{ route('public.reports.search') }}" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold transition-opacity hover:opacity-90" style="color: var(--primary-color);">
                            Go to Patient Reports
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($appointment->booking_source === 'public' && in_array($appointment->status, ['scheduled', 'confirmed'], true) && ! $appointment->canBeCancelledByPatient())
            <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50/90 p-5 shadow-sm">
                <p class="text-sm text-slate-700 leading-relaxed">
                    <strong class="text-slate-900">Visit time has passed.</strong> Online cancellation is only available before your scheduled slot. If you still need help, please contact the lab.
                </p>
            </div>
        @endif

        @if($appointment->canBeCancelledByPatient())
            <div class="mt-8 rounded-2xl border border-amber-200/90 bg-gradient-to-br from-amber-50/95 to-orange-50/40 p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 ring-1 ring-amber-200/80">
                        <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-display text-lg font-semibold text-amber-950">Need to cancel?</h3>
                        <p class="text-sm text-amber-900/85 mt-1 leading-relaxed">You can cancel this appointment if your plans change. Please cancel at least <strong>24 hours</strong> in advance when possible.</p>
                        <form method="POST" action="{{ route('public.appointments.cancel', $appointment) }}" onsubmit="return confirm('Cancel this appointment? This cannot be undone.');" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-600/20 transition hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                                Cancel appointment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <nav class="mt-10 flex flex-col sm:flex-row flex-wrap items-center justify-center gap-3 sm:gap-4 text-center" aria-label="Appointment navigation">
            <a href="{{ route('public.appointments.search') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 shadow-sm hover:bg-slate-50 transition">
                ← My appointments
            </a>
            <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:brightness-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2"
                style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #1e293b) 100%);">
                Book another visit
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                Home
            </a>
        </nav>
    </div>
</section>
@endsection
