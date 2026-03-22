@extends('website.layout')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="bg-white shadow-lg rounded-2xl p-8 md:p-10 border border-gray-100 overflow-hidden">
        <!-- Step indicator -->
        <div class="flex items-center justify-center gap-3 mb-6">
            <div class="flex items-center">
                <span class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold text-white shadow-md" style="background-color: var(--primary-color);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </span>
                <span class="ml-2 text-sm font-semibold text-gray-900">Step 1</span>
            </div>
            <span class="w-8 h-0.5 bg-gray-300 rounded" aria-hidden="true"></span>
            <div class="flex items-center opacity-50">
                <span class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold border-2 border-gray-300 text-gray-400">2</span>
                <span class="ml-2 text-sm font-medium text-gray-400">Results</span>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="p-3 rounded-xl" style="background-color: color-mix(in srgb, var(--primary-color) 15%, transparent);">
                <svg class="w-10 h-10" style="color: var(--primary-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 text-center">View Your Reports</h1>
        <p class="text-gray-600 mb-6 text-center max-w-md mx-auto">Enter the phone number you used when booking. Reports appear here once they are ready.</p>

        <form method="POST" action="{{ route('public.reports.results') }}" class="space-y-6">
            @csrf

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                        placeholder="e.g. 9876543210"
                        inputmode="numeric"
                        autocomplete="tel"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-offset-0 pl-12 pr-4 py-3.5 text-lg"
                        style="--tw-ring-color: var(--primary-color);">
                </div>
                <p class="mt-1.5 text-sm text-gray-500">Use the number registered with us for your visit or appointment.</p>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full text-white font-semibold py-3.5 px-6 rounded-xl text-lg transition-all hover:opacity-90 hover:shadow-lg flex items-center justify-center gap-2"
                style="background-color: var(--primary-color);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Find my reports
            </button>
        </form>

        @if(session('error'))
            <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                <p class="mt-2 text-sm text-red-700">Please check the number or <a href="{{ url('/contact') }}" class="underline font-medium">contact us</a> if you need help.</p>
            </div>
        @endif

        <p class="mt-6 text-center text-sm text-gray-500">
            Can’t find your reports? <a href="{{ url('/contact') }}" class="font-medium" style="color: var(--primary-color);">Contact us</a>
        </p>
    </div>
</div>
@endsection
