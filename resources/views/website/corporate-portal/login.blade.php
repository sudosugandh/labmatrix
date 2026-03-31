@extends('website.layout')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="bg-white shadow-lg rounded-2xl p-8 md:p-10 border border-gray-100">
        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl mb-4" style="background: color-mix(in srgb, var(--primary-color) 15%, transparent);">
                <svg class="w-8 h-8" style="color: var(--primary-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Employer portal</h1>
            <p class="text-gray-600 mt-2 text-sm">Sign in with your corporate contact phone and portal password.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('corporate.portal.login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required autocomplete="tel"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>
            <button type="submit" class="w-full rounded-xl py-3 text-sm font-semibold text-white shadow-md hover:opacity-95 transition-opacity"
                style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e1b4b) 100%);">
                Sign in
            </button>
        </form>
        <p class="mt-6 text-center text-xs text-gray-500">
            <a href="{{ route('home') }}" class="underline hover:text-gray-700">Back to website</a>
        </p>
    </div>
</div>
@endsection
