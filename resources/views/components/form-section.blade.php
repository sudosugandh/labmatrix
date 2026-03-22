@props(['title', 'description' => null])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    @if($title)
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @if($description)
                <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
