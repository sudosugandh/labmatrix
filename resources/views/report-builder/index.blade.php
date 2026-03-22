<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Custom Reports</h2>
                <p class="text-sm text-gray-500 mt-1">Create and manage custom reports</p>
            </div>
            <a href="{{ route('report-builder.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Report
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Report Type</label>
                <select id="type-filter" class="rounded-lg border-gray-300 text-sm">
                    <option value="">All Types</option>
                    @foreach($reportTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Visibility</label>
                <select id="visibility-filter" class="rounded-lg border-gray-300 text-sm">
                    <option value="">All</option>
                    <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>My Reports</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    @if($templates->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($templates as $template)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $template->name }}</h3>
                                @if($template->description)
                                    <p class="text-sm text-gray-600">{{ Str::limit($template->description, 80) }}</p>
                                @endif
                            </div>
                            @if($template->is_default)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                    Default
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ $reportTypes[$template->report_type] ?? $template->report_type }}
                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $template->usage_count }} uses
                            </span>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-2">
                                @if($template->is_public)
                                    <span class="text-xs text-gray-500">Public</span>
                                @else
                                    <span class="text-xs text-gray-500">Private</span>
                                @endif
                                @if($template->creator)
                                    <span class="text-xs text-gray-500">• by {{ $template->creator->name }}</span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('report-builder.create', ['template' => $template->id]) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Use
                                </a>
                                <a href="{{ route('report-builder.edit', $template) }}" 
                                   class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('report-builder.destroy', $template) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this report template?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $templates->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">No report templates</h3>
            <p class="mt-2 text-sm text-gray-500">Get started by creating your first custom report.</p>
            <div class="mt-6">
                <a href="{{ route('report-builder.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                    Create New Report
                </a>
            </div>
        </div>
    @endif
</x-app-layout>

<script>
document.getElementById('type-filter')?.addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('type', this.value);
    } else {
        url.searchParams.delete('type');
    }
    window.location = url;
});

document.getElementById('visibility-filter')?.addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('visibility', this.value);
    } else {
        url.searchParams.delete('visibility');
    }
    window.location = url;
});
</script>
