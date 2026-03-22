@props(['href', 'icon', 'title', 'color' => 'blue', 'method' => 'GET'])

@if($method === 'GET')
    <a href="{{ $href }}" 
        class="p-2 text-{{ $color }}-600 hover:bg-{{ $color }}-50 rounded-lg transition-colors" 
        title="{{ $title }}">
        {{ $icon }}
    </a>
@else
    <form action="{{ $href }}" method="POST" class="inline">
        @csrf
        @method($method)
        <button type="submit" 
            class="p-2 text-{{ $color }}-600 hover:bg-{{ $color }}-50 rounded-lg transition-colors" 
            title="{{ $title }}"
            onclick="return confirm('Are you sure?')">
            {{ $icon }}
        </button>
    </form>
@endif
