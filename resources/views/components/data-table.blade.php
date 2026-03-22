@props(['title' => null, 'icon' => null, 'emptyMessage' => 'No data found.', 'emptyAction' => null])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    @if($title || $icon)
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            @if($icon)
                {!! $icon !!}
            @endif
            @if($title)
                <span class="{{ $icon ? 'ml-2' : '' }}">{{ $title }}</span>
            @endif
        </h3>
    </div>
    @endif
    <div class="overflow-x-auto">
        @if(isset($slot) && !empty($slot))
            {{ $slot }}
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="mt-4 text-gray-500 text-lg">{{ $emptyMessage }}</p>
                @if($emptyAction)
                    {{ $emptyAction }}
                @endif
            </div>
        @endif
    </div>
</div>
