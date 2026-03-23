@extends('website.layout')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <nav class="mb-8">
            <a href="{{ route('public.packages.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/70 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                All packages
            </a>
        </nav>
        @if($testPackage->discount > 0)
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold text-white mb-6 shadow-lg" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e293b) 100%);">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.318c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.318c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                {{ number_format($testPackage->discount, 0) }}% off — bundle pricing
            </div>
        @endif
        <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-white tracking-tight mb-4">{{ $testPackage->name }}</h1>
        @if($testPackage->description)
            <p class="text-lg text-slate-300 max-w-3xl leading-relaxed">{{ $testPackage->description }}</p>
        @endif
    </div>
</section>

<section class="relative bg-gradient-to-b from-slate-50 to-white py-12 md:py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-12">
            {{-- Main: tests --}}
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-2">What’s included</h2>
                    <p class="text-slate-600 text-sm">Each test is performed with the same quality standards as standalone bookings.</p>
                </div>

                <div class="space-y-3">
                    @foreach($testPackage->tests as $test)
                        <div class="group flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all duration-200">
                            <div class="flex items-start gap-4 min-w-0">
                                <span class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-bold" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #334155) 100%);">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-slate-900">{{ $test->name }}</h3>
                                    @if($test->description)
                                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ Str::limit($test->description, 160) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-left sm:text-right pl-14 sm:pl-0">
                                <p class="text-xs uppercase tracking-wide text-slate-500">If booked alone</p>
                                <p class="text-lg font-semibold tabular-nums" style="color: var(--primary-color);">₹{{ number_format($test->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar: pricing & CTA --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xl shadow-slate-200/50 p-6 md:p-8">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Package price</p>
                        @if($testPackage->discount > 0)
                            <p class="text-lg text-slate-400 line-through mb-1">₹{{ number_format($testPackage->price, 2) }}</p>
                        @endif
                        <p class="text-4xl md:text-5xl font-bold tabular-nums mb-2" style="color: var(--primary-color);">₹{{ number_format($testPackage->final_price, 2) }}</p>
                        <p class="text-sm text-slate-600 mb-6">{{ $testPackage->tests->count() }} {{ Str::plural('test', $testPackage->tests->count()) }} in this bundle</p>

                        @php
                            $individualTotal = $testPackage->tests->sum('price');
                            $savings = $individualTotal - $testPackage->final_price;
                        @endphp
                        @if($savings > 0)
                            <div class="rounded-xl p-4 mb-6 border border-emerald-200/80 bg-emerald-50/80">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <p class="font-semibold text-emerald-900">You save ₹{{ number_format($savings, 2) }}</p>
                                        <p class="text-xs text-emerald-800/90 mt-1">Individual total would be ₹{{ number_format($individualTotal, 2) }}.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('public.appointments.create', ['type' => 'patient', 'package' => $testPackage->id]) }}" class="flex w-full items-center justify-center gap-2 rounded-xl py-4 px-6 text-base font-semibold text-white shadow-lg transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e1b4b) 100%); box-shadow: 0 4px 24px color-mix(in srgb, var(--primary-color) 40%, transparent);">
                            Book this package
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </a>
                        <p class="text-xs text-slate-500 text-center mt-4">You’ll complete patient details on the next step.</p>
                    </div>

                    <div class="rounded-2xl bg-slate-900 text-white p-6">
                        <h3 class="font-display font-semibold mb-2">Questions?</h3>
                        <p class="text-sm text-slate-400 mb-4">Our team can help you choose the right package.</p>
                        <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-white/90 hover:text-white">
                            Contact us
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
