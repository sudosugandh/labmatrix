@props(['label' => null, 'title' => null, 'value', 'icon' => null, 'color' => 'indigo', 'change' => null, 'changeLabel' => 'vs previous'])

@php
    // Support both 'label' and 'title' props
    $displayLabel = $label ?? $title ?? '';
    
    // Icon mapping for common icon names - using inline styles for dynamic colors
    $iconColorClass = match($color) {
        'indigo' => 'text-indigo-600',
        'green' => 'text-green-600',
        'yellow' => 'text-yellow-600',
        'red' => 'text-red-600',
        'blue' => 'text-blue-600',
        'purple' => 'text-purple-600',
        'orange' => 'text-orange-600',
        default => 'text-indigo-600',
    };
    
    $iconMap = [
        'package' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
        'check-circle' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'exclamation-triangle' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        'x-circle' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'currency-dollar' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'document-text' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
        'clock' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'check' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'arrow-down' => '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>',
    ];
    
    $iconHtml = $icon;
    if (is_string($icon) && isset($iconMap[$icon])) {
        $iconHtml = $iconMap[$icon];
    } elseif (empty($iconHtml)) {
        $iconHtml = '<svg class="w-6 h-6 ' . $iconColorClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>';
    }
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $displayLabel }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $value }}</p>
            @if($change !== null)
                <div class="mt-2 flex items-center">
                    @if($change >= 0)
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-sm font-semibold text-green-600">{{ abs($change) }}%</span>
                    @else
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                        <span class="text-sm font-semibold text-red-600">{{ abs($change) }}%</span>
                    @endif
                    <span class="text-xs text-gray-500 ml-1">{{ $changeLabel }}</span>
                </div>
            @endif
        </div>
        @if($iconHtml)
            <div class="p-3 bg-{{ $color }}-100 rounded-lg">
                {!! $iconHtml !!}
            </div>
        @endif
    </div>
</div>
