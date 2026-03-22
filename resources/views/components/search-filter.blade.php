@props(['route' => null, 'searchPlaceholder' => 'Search...'])

@php
    // Default to current route if not provided
    $formRoute = $route ?? request()->url();
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ $formRoute }}" class="space-y-4">
        {{ $slot }}
        
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
                @if(request()->hasAny(array_keys(request()->except(['page']))))
                    <a href="{{ $formRoute }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                        Clear
                    </a>
                @endif
            </div>
            @if(isset($resultCount))
                <div class="text-sm text-gray-500">
                    {{ $resultCount }}
                </div>
            @endif
        </div>
    </form>
</div>
