@props(['count' => 3])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @for($i = 0; $i < $count; $i++)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="h-4 bg-gray-200 rounded w-1/3 mb-4 animate-pulse"></div>
            <div class="h-8 bg-gray-200 rounded w-1/2 mb-2 animate-pulse" style="animation-delay: 0.1s"></div>
            <div class="h-3 bg-gray-200 rounded w-2/3 animate-pulse" style="animation-delay: 0.2s"></div>
        </div>
    @endfor
</div>
