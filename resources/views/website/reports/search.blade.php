@extends('website.layout')

@section('content')
<section class="relative overflow-hidden border-b border-slate-800/50 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="absolute inset-0 opacity-[0.06] bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-14 md:pt-12 md:pb-16 text-center">
        <nav class="flex flex-wrap items-center justify-center gap-2 text-xs font-semibold uppercase tracking-wider text-white/50 mb-4" aria-label="Steps">
            <span class="rounded-full px-2.5 py-0.5 bg-white/10 text-white/90">1 · Phone</span>
            <span class="text-white/30" aria-hidden="true">→</span>
            <span class="rounded-full px-2.5 py-0.5 border border-white/20 text-white/60">2 · Results</span>
        </nav>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-white tracking-tight [text-wrap:balance]">View your reports</h1>
        <p class="mt-2 text-slate-300 text-sm sm:text-base max-w-md mx-auto leading-relaxed">Enter the phone number linked to your visit. PDFs show up here only after the lab releases them.</p>
    </div>
    <div class="relative w-full overflow-hidden leading-[0] text-slate-50">
        <svg class="relative block w-full h-[28px] sm:h-[36px]" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M0,96L60,88C120,80,240,64,360,58.7C480,53,600,59,720,64C840,69,960,75,1080,69.3C1200,64,1320,48,1380,40L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"/>
        </svg>
    </div>
</section>

<section class="relative bg-gradient-to-b from-slate-50 to-white -mt-px pb-16 md:pb-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 md:-mt-10 relative z-10">
        <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 md:p-10 border border-slate-200/80">
            <ul class="mb-6 space-y-2 text-sm text-slate-600 border border-slate-100 rounded-xl bg-slate-50/80 px-4 py-3">
                <li class="flex gap-2"><span class="text-emerald-600 shrink-0" aria-hidden="true">✓</span> We only use your number to match existing records — nothing is posted publicly.</li>
                <li class="flex gap-2"><span class="text-emerald-600 shrink-0" aria-hidden="true">✓</span> Try 10 digits or include +91 if that’s how you registered.</li>
            </ul>

            <form method="POST" action="{{ route('public.reports.results') }}" class="space-y-6" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none" aria-hidden="true">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                            placeholder="e.g. 9876543210 or +91…"
                            inputmode="tel"
                            autocomplete="tel"
                            class="block w-full min-h-[52px] rounded-xl border border-slate-200 shadow-sm focus:border-slate-900 focus:ring-2 focus:ring-slate-900/15 focus:ring-offset-0 pl-12 pr-4 py-3.5 text-base sm:text-lg text-slate-900">
                    </div>
                    <p class="mt-1.5 text-sm text-slate-500">Use the number registered with us for your visit or appointment.</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full min-h-[52px] text-white font-semibold py-3.5 px-6 rounded-xl text-base sm:text-lg transition-all hover:opacity-95 hover:shadow-lg flex items-center justify-center gap-2 disabled:opacity-70 disabled:pointer-events-none touch-manipulation"
                    style="background-color: var(--primary-color);" :disabled="submitting">
                    <svg x-show="!submitting" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span x-show="!submitting">Find my reports</span>
                    <span x-show="submitting" x-cloak class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Searching…
                    </span>
                </button>
            </form>

            @if(session('error'))
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4" role="alert">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    <p class="mt-2 text-sm text-red-700">Please check the number or <a href="{{ url('/contact') }}" class="underline font-medium">contact us</a> if you need help.</p>
                </div>
            @endif

            @if(session('info'))
                <div class="mt-6 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sky-900 text-sm" role="status">
                    {{ session('info') }}
                </div>
            @endif

            <div class="mt-8 pt-6 border-t border-slate-100 text-center space-y-3">
                <p class="text-sm text-slate-600">Looking for a booking instead?</p>
                <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center justify-center gap-2 text-sm font-semibold transition-opacity hover:opacity-90" style="color: var(--primary-color);">
                    Book an appointment
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <p class="text-xs text-slate-500">Can’t find your reports? <a href="{{ url('/contact') }}" class="font-medium" style="color: var(--primary-color);">Contact us</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
