<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Report Template</h2>
                <p class="text-sm text-gray-500 mt-1">Update your custom report template</p>
            </div>
            <a href="{{ route('report-builder.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                ← Back to Reports
            </a>
        </div>
    </x-slot>

    <form id="report-builder-form" method="POST" action="{{ route('report-builder.update', $template) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Sidebar - Configuration -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Configuration</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                            <input type="text" value="{{ $reportTypes[$template->report_type] ?? $template->report_type }}" disabled class="block w-full rounded-lg border-gray-300 bg-gray-50">
                            <input type="hidden" name="report_type" value="{{ $template->report_type }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Name *</label>
                            <input type="text" name="name" value="{{ old('name', $template->name) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $template->description) }}</textarea>
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_public" value="1" {{ old('is_public', $template->is_public) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Make public</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" value="1" {{ old('is_default', $template->is_default) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Set as default</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Field Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Select Fields</h3>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="selectAllFields()" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Select All</button>
                            <span class="text-gray-300">|</span>
                            <button type="button" onclick="deselectAllFields()" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Deselect All</button>
                        </div>
                    </div>
                    
                    <div id="fields-container" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($availableFields as $field => $config)
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" 
                                       name="selected_fields[]" 
                                       value="{{ $field }}"
                                       {{ in_array($field, old('selected_fields', $template->selected_fields ?? [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 field-checkbox">
                                <span class="ml-2 text-sm text-gray-700">{{ $config['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" onclick="previewReport()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Preview Report
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>

<script>
function selectAllFields() {
    document.querySelectorAll('.field-checkbox').forEach(cb => cb.checked = true);
}

function deselectAllFields() {
    document.querySelectorAll('.field-checkbox').forEach(cb => cb.checked = false);
}

async function previewReport() {
    // Similar to create view - implement preview functionality
    alert('Preview functionality - to be implemented');
}
</script>
