@extends('website.layout')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center py-16 md:py-24 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl">
        {{-- Step indicator --}}
        <div class="flex justify-center mb-6" aria-label="Booking progress">
            <div class="flex flex-wrap items-center justify-center gap-x-2 gap-y-2 sm:gap-3 text-sm">
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 font-semibold text-white shadow-md" style="background: var(--primary-color);">1 · Choose type</span>
                <span class="text-slate-300 hidden sm:inline" aria-hidden="true">→</span>
                <span class="inline-flex items-center rounded-full border-2 border-slate-200 px-3 py-1.5 font-medium text-slate-500">2 · Details</span>
            </div>
        </div>

        <div class="text-center mb-10">
            <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-3 [text-wrap:balance]">Book an appointment</h1>
            <p class="text-lg text-slate-600 max-w-xl mx-auto leading-relaxed">Are you booking for yourself or as a doctor for your patient?</p>
            <p class="mt-3 text-sm text-slate-500 max-w-lg mx-auto">Your details stay private and are used only for scheduling and your care.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Patient Booking --}}
            <a href="{{ route('public.appointments.create', ['type' => 'patient']) }}"
                class="group relative bg-white rounded-2xl p-8 md:p-10 border-2 border-slate-200/80 shadow-lg shadow-slate-200/50 overflow-hidden hover:shadow-xl hover:shadow-slate-200/60 hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 min-h-[280px] flex flex-col touch-manipulation">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 6%, transparent) 0%, transparent 60%);"></div>
                <div class="relative text-center flex-1 flex flex-col justify-center">
                    <div class="mx-auto w-20 h-20 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e1b4b) 100%);">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl md:text-2xl font-bold text-slate-900 mb-2">I'm a Patient</h3>
                    <p class="text-slate-600 mb-6">Book an appointment for yourself</p>
                    <span class="inline-flex items-center gap-2 font-semibold group-hover:gap-3 transition-all" style="color: var(--primary-color);">
                        Continue
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </div>
            </a>

            {{-- Doctor Booking --}}
            <a href="{{ route('public.appointments.create', ['type' => 'doctor']) }}"
                class="group relative bg-white rounded-2xl p-8 md:p-10 border-2 border-slate-200/80 shadow-lg shadow-slate-200/50 overflow-hidden hover:shadow-xl hover:shadow-slate-200/60 hover:border-emerald-300 hover:-translate-y-1 transition-all duration-300 min-h-[280px] flex flex-col touch-manipulation">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-br from-emerald-50/80 to-transparent"></div>
                <div class="relative text-center flex-1 flex flex-col justify-center">
                    <div class="mx-auto w-20 h-20 rounded-2xl flex items-center justify-center mb-6 shadow-lg bg-gradient-to-br from-emerald-500 to-emerald-700 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4a2 2 0 114 0v2a2 2 0 01-4 0V4zm10 0a2 2 0 114 0v2a2 2 0 01-4 0V4z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 6l3 2 3-2"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v10"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18h6"></path>
                            <circle cx="12" cy="20" r="2" stroke-width="2"></circle>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl md:text-2xl font-bold text-slate-900 mb-2">I'm a Doctor</h3>
                    <p class="text-slate-600 mb-6">Book an appointment for your patient</p>
                    <span class="inline-flex items-center gap-2 font-semibold text-emerald-600 group-hover:gap-3 transition-all">
                        Continue
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </div>
            </a>
        </div>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 text-sm">
            <a href="{{ route('public.reports.search') }}" class="inline-flex items-center gap-2 font-semibold transition-opacity hover:opacity-90" style="color: var(--primary-color);">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                View lab reports
            </a>
            <span class="hidden sm:inline text-slate-300" aria-hidden="true">|</span>
            <a href="{{ route('public.appointments.search') }}" class="text-slate-600 hover:text-slate-900 font-medium transition-colors">Already booked? My appointments</a>
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to home
            </a>
        </div>
    </div>
</div>
@endsection
