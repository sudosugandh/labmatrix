@props(['rows' => 5])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Table Header -->
    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
        <div class="grid grid-cols-5 gap-4">
            @for($i = 0; $i < 5; $i++)
                <div class="h-4 bg-gray-300 rounded animate-pulse"></div>
            @endfor
        </div>
    </div>
    
    <!-- Table Rows -->
    <div class="divide-y divide-gray-200">
        @for($j = 0; $j < $rows; $j++)
            <div class="px-6 py-4">
                <div class="grid grid-cols-5 gap-4">
                    @for($i = 0; $i < 5; $i++)
                        <div class="h-4 bg-gray-200 rounded animate-pulse" style="animation-delay: {{ $i * 0.1 }}s"></div>
                    @endfor
                </div>
            </div>
        @endfor
    </div>
</div>
