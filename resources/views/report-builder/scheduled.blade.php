<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Scheduled Reports</h2>
                <p class="text-sm text-gray-500 mt-1">Automated report generation and email delivery</p>
            </div>
            <a href="{{ route('report-builder.scheduled.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Schedule New Report
            </a>
        </div>
    </x-slot>

    @if($scheduledReports->count() > 0)
        <div class="space-y-4">
            @foreach($scheduledReports as $scheduled)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $scheduled->name }}</h3>
                                @if($scheduled->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            
                            @if($scheduled->description)
                                <p class="text-sm text-gray-600 mb-3">{{ $scheduled->description }}</p>
                            @endif
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Template:</span>
                                    <span class="font-medium text-gray-900">{{ $scheduled->template->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Frequency:</span>
                                    <span class="font-medium text-gray-900">{{ ucfirst($scheduled->frequency) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Format:</span>
                                    <span class="font-medium text-gray-900">{{ strtoupper($scheduled->format) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Send Time:</span>
                                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($scheduled->send_time)->format('g:i A') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Next Run:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $scheduled->next_run_at ? $scheduled->next_run_at->format('M d, Y g:i A') : 'Not scheduled' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Last Run:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $scheduled->last_run_at ? $scheduled->last_run_at->format('M d, Y g:i A') : 'Never' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Run Count:</span>
                                    <span class="font-medium text-gray-900">{{ $scheduled->run_count }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Recipients:</span>
                                    <span class="font-medium text-gray-900">{{ count($scheduled->recipients) }} email(s)</span>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <span class="text-xs text-gray-500">Recipients: </span>
                                <span class="text-xs text-gray-700">{{ implode(', ', $scheduled->recipients) }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 ml-4">
                            <form method="POST" action="{{ route('report-builder.scheduled.toggle', $scheduled) }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-{{ $scheduled->is_active ? 'yellow' : 'green' }}-600 hover:bg-{{ $scheduled->is_active ? 'yellow' : 'green' }}-50 rounded-lg transition-colors" title="{{ $scheduled->is_active ? 'Deactivate' : 'Activate' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($scheduled->is_active)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('report-builder.scheduled.destroy', $scheduled) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this scheduled report?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $scheduledReports->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">No scheduled reports</h3>
            <p class="mt-2 text-sm text-gray-500">Create scheduled reports to automatically generate and email reports.</p>
            <div class="mt-6">
                <a href="{{ route('report-builder.scheduled.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                    Schedule New Report
                </a>
            </div>
        </div>
    @endif
</x-app-layout>
