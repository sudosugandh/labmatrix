@extends('website.layout')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center py-16 md:py-24 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl">
        {{-- Step indicator --}}
        <div class="flex justify-center mb-8">
            <div class="flex items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold text-white shadow-lg" style="background: var(--primary-color);">1</span>
                <span class="text-slate-400 font-medium">Choose type</span>
                <span class="w-12 h-0.5 bg-slate-200 mx-1"></span>
                <span class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold border-2 border-slate-200 text-slate-400">2</span>
                <span class="text-slate-400 font-medium">Book</span>
            </div>
        </div>

        <div class="text-center mb-12">
            <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-3">Book an Appointment</h1>
            <p class="text-lg text-slate-600 max-w-xl mx-auto">Are you booking for yourself or as a doctor for your patient?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Patient Booking --}}
            <a href="{{ route('public.appointments.create', ['type' => 'patient']) }}"
                class="group relative bg-white rounded-2xl p-8 md:p-10 border-2 border-slate-200/80 shadow-lg shadow-slate-200/50 overflow-hidden hover:shadow-xl hover:shadow-slate-200/60 hover:border-slate-300 hover:-translate-y-1 transition-all duration-300">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 6%, transparent) 0%, transparent 60%);"></div>
                <div class="relative text-center">
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
                class="group relative bg-white rounded-2xl p-8 md:p-10 border-2 border-slate-200/80 shadow-lg shadow-slate-200/50 overflow-hidden hover:shadow-xl hover:shadow-slate-200/60 hover:border-emerald-300 hover:-translate-y-1 transition-all duration-300">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-br from-emerald-50/80 to-transparent"></div>
                <div class="relative text-center">
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

        <div class="mt-12 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
