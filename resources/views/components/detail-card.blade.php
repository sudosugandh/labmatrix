@props(['title', 'gradient' => 'from-indigo-500 to-purple-600'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    @if($title)
        <div class="px-6 py-4 bg-gradient-to-r {{ $gradient }}">
            <h3 class="text-lg font-bold text-white flex items-center">
                @isset($icon)
                    <span class="mr-2">{!! $icon !!}</span>
                @endisset
                <span>{{ $title }}</span>
            </h3>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
