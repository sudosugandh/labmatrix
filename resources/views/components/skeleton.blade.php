@props(['variant' => 'text', 'width' => 'full', 'height' => null])

@php
    $widthClasses = [
        'full' => 'w-full',
        '1/2' => 'w-1/2',
        '1/3' => 'w-1/3',
        '2/3' => 'w-2/3',
        '1/4' => 'w-1/4',
        '3/4' => 'w-3/4',
        'auto' => 'w-auto'
    ];
    
    $heightClasses = [
        'text' => 'h-4',
        'title' => 'h-6',
        'heading' => 'h-8',
        'avatar' => 'h-12 w-12 rounded-full',
        'button' => 'h-10',
        'card' => 'h-32'
    ];
    
    $baseClasses = 'animate-pulse bg-gray-200 rounded';
    $widthClass = $widthClasses[$width] ?? $widthClasses['full'];
    $heightClass = $height ? "h-{$height}" : ($heightClasses[$variant] ?? $heightClasses['text']);
@endphp

<div class="{{ $baseClasses }} {{ $widthClass }} {{ $heightClass }}"></div>
