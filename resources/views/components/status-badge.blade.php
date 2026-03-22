@props(['status', 'type' => 'default'])

@php
    $colors = [
        'default' => [
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'ready' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'paid' => 'bg-green-100 text-green-800',
            'partial' => 'bg-yellow-100 text-yellow-800',
        ],
        'appointment' => [
            'scheduled' => 'bg-blue-100 text-blue-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'completed' => 'bg-purple-100 text-purple-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'no_show' => 'bg-gray-100 text-gray-800',
        ],
        'order' => [
            'pending' => 'bg-gray-100 text-gray-800',
            'sample_collected' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
        ],
    ];
    
    $statusKey = strtolower(str_replace(' ', '_', $status));
    $colorClass = $colors[$type][$statusKey] ?? $colors['default'][$statusKey] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
