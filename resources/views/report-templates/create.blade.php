<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Create Report Template</h2>
                <p class="text-sm text-gray-500 mt-1">Define a new template for diagnostic reports</p>
            </div>
            <a href="{{ route('report-templates.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                ← Back to Templates
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('report-templates.store') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name *</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        placeholder="e.g., CBC Template, Lipid Profile Template"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Give your template a descriptive name</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Brief description of what this template is used for..."
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="template_type" class="block text-sm font-medium text-gray-700 mb-2">Template Type *</label>
                        <select name="template_type" id="template_type" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 @error('template_type') border-red-500 @enderror">
                            <option value="simple" {{ old('template_type') == 'simple' ? 'selected' : '' }}>Simple - Basic parameter table</option>
                            <option value="panel" {{ old('template_type') == 'panel' ? 'selected' : '' }}>Panel - Grouped parameters (e.g., Liver Panel)</option>
                            <option value="advanced" {{ old('template_type') == 'advanced' ? 'selected' : '' }}>Advanced - Parameters + calculations + interpretation</option>
                        </select>
                        @error('template_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="layout_type" class="block text-sm font-medium text-gray-700 mb-2">Layout Type *</label>
                        <select name="layout_type" id="layout_type" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 @error('layout_type') border-red-500 @enderror">
                            <option value="table" {{ old('layout_type') == 'table' ? 'selected' : '' }}>Table - Standard table format</option>
                            <option value="grouped" {{ old('layout_type') == 'grouped' ? 'selected' : '' }}>Grouped - Parameters grouped by category</option>
                            <option value="mixed" {{ old('layout_type') == 'mixed' ? 'selected' : '' }}>Mixed - Combination of formats</option>
                        </select>
                        @error('layout_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_default" id="is_default" value="1"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_default" class="ml-2 block text-sm text-gray-700">
                        Set as default template for this lab
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('report-templates.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Create Template
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
