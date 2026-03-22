@props(['route', 'label' => 'Export CSV', 'icon' => true])

<a href="{{ $route }}?{{ http_build_query(request()->except(['page'])) }}" 
   class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
    @if($icon)
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    @endif
    {{ $label }}
</a>
