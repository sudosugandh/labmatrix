@props(['label', 'value' => null])

<div>
    <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
        @isset($icon)
            <span class="mr-2">{!! $icon !!}</span>
        @endisset
        {{ $label }}
    </dt>
    <dd class="mt-1 text-base font-semibold text-gray-900">
        {{ $value ?? $slot }}
    </dd>
</div>
