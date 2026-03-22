@props(['loading' => false, 'text' => 'Loading...'])

<button 
    {{ $attributes->merge(['class' => 'inline-flex items-center px-6 py-2.5 font-semibold rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}
    @if($loading) disabled @endif
>
    @if($loading)
        <x-loading-spinner size="sm" color="white" class="mr-2" />
        <span>{{ $text }}</span>
    @else
        {{ $slot }}
    @endif
</button>
