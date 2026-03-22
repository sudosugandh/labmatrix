@props(['label', 'name', 'value' => '1', 'checked' => false, 'help' => null])

@php
    $isChecked = $checked || old($name);
@endphp

<div>
    <div class="flex items-center">
        <input 
            type="checkbox" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ $value }}"
            {{ $isChecked ? 'checked' : '' }}
            {{ $attributes->merge(['class' => 'h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded']) }}
        >
        <label for="{{ $name }}" class="ml-2 block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    </div>
    @if($help)
        <p class="mt-1 text-xs text-gray-500 ml-6">{{ $help }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-sm text-red-600 flex items-center ml-6">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
