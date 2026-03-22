@props(['label', 'name', 'type' => 'text', 'required' => false, 'help' => null, 'placeholder' => null, 'value' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value ?? '') }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 transition-colors']) }}
    >
    @if($help)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-sm text-red-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
