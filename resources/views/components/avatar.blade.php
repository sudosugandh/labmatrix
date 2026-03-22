@props(['name', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-12 w-12 text-base',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $initial = strtoupper(substr($name, 0, 1));
@endphp

<div class="flex-shrink-0 {{ $sizeClass }} rounded-full bg-indigo-100 flex items-center justify-center">
    <span class="text-indigo-600 font-semibold">{{ $initial }}</span>
</div>
