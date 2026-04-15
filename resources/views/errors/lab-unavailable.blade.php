<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($reason ?? '') === 'suspended' ? 'Account suspended' : 'Subscription inactive' }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-100">
        <div class="w-full sm:max-w-lg mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-xl text-center border border-slate-200/80">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            @if(($reason ?? '') === 'suspended')
                <h1 class="text-2xl font-bold text-gray-900 mb-2">This lab account is suspended</h1>
                <p class="text-gray-600 mb-2">@if(!empty($labName))<span class="font-medium text-gray-800">{{ $labName }}</span> is @else This site is @endif not available right now.</p>
                <p class="text-sm text-gray-500">Please contact your laboratory or platform support if you need access.</p>
            @else
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Subscription inactive</h1>
                <p class="text-gray-600 mb-2">@if(!empty($labName))<span class="font-medium text-gray-800">{{ $labName }}</span> — @endif This service is temporarily unavailable.</p>
                <p class="text-sm text-gray-500">The lab&rsquo;s subscription may have ended or needs renewal. Please contact the lab or support.</p>
            @endif
        </div>
    </div>
</body>
</html>
