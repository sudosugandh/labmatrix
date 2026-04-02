@extends('website.layout')

@section('content')
{{-- Success hero --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
    <div class="absolute top-1/2 right-0 w-72 h-72 rounded-full blur-3xl opacity-25 pointer-events-none" style="background: color-mix(in srgb, var(--primary-color) 45%, transparent);"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
            <div class="flex gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-500/20 ring-1 ring-emerald-400/30">
                    <svg class="h-8 w-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold tracking-widest uppercase text-white/50 mb-1">Confirmed</p>
                    <h1 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-white tracking-tight">Appointment booked successfully</h1>
                    <p class="mt-2 text-slate-300 text-sm sm:text-base max-w-xl">Your visit is on our calendar. Save your reference ID and review the details below.</p>
                </div>
            </div>
            <div class="sm:text-right shrink-0">
                <p class="text-xs font-medium uppercase tracking-wider text-white/50">Reference</p>
                <p class="mt-1 font-display text-2xl font-bold tabular-nums text-white">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    <div class="relative w-full overflow-hidden leading-[0] text-slate-50">
        <svg class="relative block w-full h-[40px] sm:h-[52px]" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M0,96L60,88C120,80,240,64,360,58.7C480,53,600,59,720,64C840,69,960,75,1080,69.3C1200,64,1320,48,1380,40L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"/>
        </svg>
    </div>
</section>

<section class="relative bg-gradient-to-b from-slate-50 to-white -mt-px pt-4 pb-16 md:pb-24">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-100/50 via-transparent to-transparent pointer-events-none"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6 rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-sm px-4 py-4 sm:px-6 sm:py-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">What happens next</p>
            <ol class="grid gap-3 sm:grid-cols-3 text-sm text-slate-700">
                <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-white" style="background: var(--primary-color);">1</span><span>We’ll confirm your slot and may reach you on the phone you provided if anything needs to change.</span></li>
                <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-white" style="background: var(--primary-color);">2</span><span>After your visit, reports appear under <strong class="text-slate-900">Patient reports</strong> when the lab releases them.</span></li>
                <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-white" style="background: var(--primary-color);">3</span><span>Save your reference number above — you can use it with <strong class="text-slate-900">My appointments</strong> anytime.</span></li>
            </ol>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-0 lg:divide-x divide-slate-100">
                {{-- Details --}}
                <div class="lg:col-span-3 p-6 sm:p-8">
                    <h2 class="font-display text-lg font-semibold text-slate-900 mb-6 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white shadow-sm" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #334155) 100%); box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08), 0 2px 8px rgba(15, 23, 42, 0.06);">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Appointment details
                    </h2>

                    <dl class="space-y-4">
                        <div class="flex gap-3 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3">
                            <span class="text-slate-400 shrink-0 mt-0.5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Patient</dt>
                                <dd class="font-semibold text-slate-900">{{ $appointment->patient->name }}</dd>
                            </div>
                        </div>
                        <div class="flex gap-3 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3">
                            <span class="text-slate-400 shrink-0 mt-0.5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Phone</dt>
                                <dd class="font-semibold text-slate-900">{{ $appointment->patient->phone }}</dd>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                    <dd class="font-semibold text-slate-900">
                                        @php
                                            $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                                            echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                                        @endphp
                                    </dd>
                                </div>
                            </div>
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
                                    <p class="mt-2 text-sm text-slate-700 bg-slate-50 border border-slate-200/80 rounded-lg px-3 py-2">
                                        <strong>Test orders created.</strong> This package includes {{ $appointment->package->tests->count() }} {{ Str::plural('test', $appointment->package->tests->count()) }}. Reports will be available when ready.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if((! $appointment->package) && $appointment->tests && $appointment->tests->count() > 0)
                        <div class="mt-6 rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Tests you selected</p>
                            <ul class="list-disc list-inside text-sm text-slate-800 space-y-1">
                                @foreach($appointment->tests as $t)
                                    <li>{{ $t->name }} <span class="text-slate-500 tabular-nums">(₹{{ number_format($t->price, 2) }})</span></li>
                                @endforeach
                            </ul>
                            <p class="mt-2 text-xs text-slate-600">Test orders are created for these tests. Reports will be available when ready.</p>
                        </div>
                    @endif

                    @if($appointment->doctor)
                        <div class="mt-6 rounded-xl border border-slate-200/80 p-4 bg-white">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Referred by</p>
                            <p class="font-semibold text-slate-900">Dr. {{ $appointment->doctor->name }}</p>
                            @if($appointment->doctor->specialization)
                                <p class="text-sm text-slate-600">{{ $appointment->doctor->specialization }}</p>
                            @endif
                            @if($appointment->doctor->clinic_name)
                                <p class="text-sm text-slate-500">{{ $appointment->doctor->clinic_name }}</p>
                            @endif
                            @if(!$appointment->doctor->is_active)
                                <span class="mt-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-900">Pending approval</span>
                            @endif
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
                                    <p class="text-xs text-slate-500 mt-0.5">Details you shared when booking.</p>
                                </div>
                            </div>
                            @if($appointment->referring_doctor_name)
                                <p class="text-sm text-slate-700"><span class="text-slate-500">Name</span> — {{ $appointment->referring_doctor_name }}</p>
                            @endif
                            @if($appointment->referring_doctor_phone)
                                <p class="text-sm text-slate-700 mt-1"><span class="text-slate-500">Phone</span> — {{ $appointment->referring_doctor_phone }}</p>
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

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <span class="text-sm text-slate-600">Status</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e293b) 100%);">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    @if($appointment->notes)
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Notes</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="lg:col-span-2 p-6 sm:p-8 bg-slate-50/50 border-t lg:border-t-0 border-slate-100">
                    <p class="text-sm font-semibold text-slate-900 mb-1">Next steps</p>
                    <p class="text-xs text-slate-500 mb-4">View full details, download a PDF receipt, or manage your bookings.</p>
                    <div class="space-y-3">
                        <a href="{{ route('public.appointments.show', $appointment) }}" class="flex items-center justify-center gap-2 w-full rounded-xl py-3.5 px-4 text-sm font-semibold text-white shadow-lg transition-all hover:shadow-xl hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e1b4b) 100%); box-shadow: 0 4px 18px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                            <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View details
                        </a>
                        <a href="{{ route('public.appointments.confirmation.download', $appointment) }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full rounded-xl py-3.5 px-4 text-sm font-semibold text-slate-900 bg-white border-2 border-slate-200 hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary-color);" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download confirmation
                        </a>
                        <a href="{{ route('public.appointments.search') }}" class="flex items-center justify-center gap-2 w-full rounded-xl py-3.5 px-4 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            My appointments
                        </a>
                        <a href="{{ route('public.reports.search') }}" class="flex items-center justify-center gap-2 w-full rounded-xl py-3.5 px-4 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Patient reports
                        </a>
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-1.5 w-full rounded-lg py-2 px-3 text-sm text-slate-500 bg-transparent hover:text-slate-700 transition-colors">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Back to home
                        </a>
                    </div>

                    <div class="mt-8 rounded-xl border border-amber-200/80 bg-amber-50/90 p-4">
                        <div class="flex gap-3">
                            <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <p class="text-sm text-amber-950/90 leading-relaxed">
                                <strong class="font-semibold">Please arrive 10 minutes early.</strong> To cancel or reschedule, contact us at least 24 hours in advance.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
