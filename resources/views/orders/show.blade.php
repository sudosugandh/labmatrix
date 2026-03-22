<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Test Order Details</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                    • Created {{ $order->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Information -->
            <x-detail-card 
                title="Order Information" 
                gradient="from-indigo-500 to-purple-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-detail-field 
                        label="Patient" 
                        :value="$order->patient->name"
                    />
                    <x-detail-field 
                        label="Test" 
                        :value="$order->test->name"
                    />
                    @if($order->package)
                    <x-detail-field 
                        label="Package" 
                        :value="$order->package->name"
                    />
                    @endif
                    <x-detail-field 
                        label="Order Status" 
                    >
                        <x-status-badge :status="$order->status" />
                    </x-detail-field>
                    <x-detail-field 
                        label="Sample Status" 
                    >
                        <x-status-badge :status="$order->sample_status ?? 'pending'" />
                    </x-detail-field>
                    <x-detail-field 
                        label="Report Status" 
                    >
                        <x-status-badge :status="$order->report_status" />
                    </x-detail-field>
                    <x-detail-field 
                        label="Order Date" 
                        :value="$order->created_at->format('M d, Y H:i')"
                    />
                    @if($order->sample_collected_at)
                    <x-detail-field 
                        label="Sample Collected At" 
                        :value="$order->sample_collected_at->format('M d, Y H:i')"
                    />
                    @endif
                    @if($order->collectedBy)
                    <x-detail-field 
                        label="Collected By" 
                        :value="$order->collectedBy->name"
                    />
                    @endif
                    @if($order->technician)
                    <x-detail-field 
                        label="Assigned Technician" 
                        :value="$order->technician->name . ($order->technician->specialization ? ' - ' . $order->technician->specialization : '')"
                    />
                    @endif
                </dl>
            </x-detail-card>

            <!-- Workflow Steps -->
            <x-detail-card 
                title="Workflow Progress" 
                gradient="from-blue-500 to-cyan-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </x-slot>
                
                <!-- Workflow Steps Visualization -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        @foreach($workflowSteps as $index => $step)
                            <div class="flex flex-col items-center flex-1">
                                <div class="relative w-full flex items-center justify-center">
                                    @if($index > 0)
                                        <div class="absolute left-0 top-1/2 w-1/2 h-1 {{ $step['status'] === 'completed' || ($step['status'] === 'current' && $workflowSteps[$index-1]['status'] === 'completed') ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                    @endif
                                    @if($index < count($workflowSteps) - 1)
                                        <div class="absolute right-0 top-1/2 w-1/2 h-1 {{ $step['status'] === 'completed' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                    @endif
                                    <div class="relative z-10 w-12 h-12 rounded-full flex items-center justify-center {{ 
                                        $step['status'] === 'completed' ? 'bg-green-500 text-white' : 
                                        ($step['status'] === 'current' ? 'bg-blue-500 text-white ring-4 ring-blue-200' : 'bg-gray-300 text-gray-600')
                                    }}">
                                        @if($step['status'] === 'completed')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @elseif($step['status'] === 'current')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <p class="text-sm font-semibold {{ $step['status'] === 'completed' ? 'text-green-600' : ($step['status'] === 'current' ? 'text-blue-600' : 'text-gray-500') }}">
                                        {{ $step['name'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if(session('error') || $errors->has('workflow'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-800">{{ session('error') ?? $errors->first('workflow') }}</p>
                    </div>
                @endif

                <!-- Quick Action Buttons -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @if($order->status === 'pending')
                        <form method="POST" action="{{ route('orders.quick-action', $order) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="collect_sample">
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mark Sample Collected
                            </button>
                        </form>
                        @endif

                        @if(($order->status === 'sample_collected' || $order->status === 'processing') && ($order->sample_status ?? 'pending') !== 'sent_to_lab')
                        <form method="POST" action="{{ route('orders.quick-action', $order) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="send_to_lab">
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Send Sample to Lab
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'sample_collected')
                        <form method="POST" action="{{ route('orders.quick-action', $order) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="start_processing">
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Start Processing
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'processing')
                        <form method="POST" action="{{ route('orders.quick-action', $order) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="complete_test">
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Complete Test
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'completed' && $order->report()->exists() && $order->report_status !== 'completed')
                        <form method="POST" action="{{ route('orders.quick-action', $order) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="complete_report">
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Mark Report Complete
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'processing' || $order->status === 'sample_collected')
                        <a href="{{ route('orders.enter-results', $order) }}" class="w-full px-4 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Enter Results
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Advanced Workflow Management (Collapsible) -->
                <details class="mb-6">
                    <summary class="cursor-pointer text-sm font-medium text-gray-700 hover:text-indigo-600 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Advanced Workflow Settings
                    </summary>

                <!-- Workflow Management Form -->
                <form method="POST" action="{{ route('orders.update-status', $order) }}" class="space-y-6" id="workflowForm">
                    @csrf
                    @method('PATCH')
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Current State:</strong> 
                            Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span> | 
                            Sample: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->sample_status ?? 'pending')) }}</span> | 
                            Report: <span class="font-semibold">{{ ucfirst($order->report_status ?? 'pending') }}</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            You can only move forward step by step. Each status must be completed before moving to the next.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Order Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 workflow-select">
                                @foreach(['pending', 'sample_collected', 'processing', 'completed', 'report_uploaded'] as $status)
                                    <option value="{{ $status }}" 
                                        {{ $order->status == $status ? 'selected' : '' }}
                                        {{ !in_array($status, $workflowInfo['statuses']) ? 'disabled' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        @if(!in_array($status, $workflowInfo['statuses']) && $order->status != $status)
                                            (Not Available)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Allowed: {{ implode(', ', array_map(fn($s) => ucfirst(str_replace('_', ' ', $s)), $workflowInfo['statuses'])) }}
                            </p>
                        </div>

                        <div>
                            <label for="sample_status" class="block text-sm font-medium text-gray-700 mb-2">Sample Status</label>
                            <select name="sample_status" id="sample_status"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 workflow-select">
                                @foreach(['pending', 'collected', 'sent_to_lab'] as $sampleStatus)
                                    <option value="{{ $sampleStatus }}" 
                                        {{ ($order->sample_status ?? 'pending') == $sampleStatus ? 'selected' : '' }}
                                        {{ !in_array($sampleStatus, $workflowInfo['sample_statuses']) ? 'disabled' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $sampleStatus)) }}
                                        @if(!in_array($sampleStatus, $workflowInfo['sample_statuses']) && ($order->sample_status ?? 'pending') != $sampleStatus)
                                            (Not Available)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Allowed: {{ implode(', ', array_map(fn($s) => ucfirst(str_replace('_', ' ', $s)), $workflowInfo['sample_statuses'])) }}
                            </p>
                        </div>

                        @if(isLargeLab())
                        <div>
                            <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-2">Assign Technician</label>
                            <select name="technician_id" id="technician_id"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5">
                                <option value="">Select Technician</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ $order->technician_id == $technician->id ? 'selected' : '' }}>
                                        {{ $technician->name }} @if($technician->specialization) - {{ $technician->specialization }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label for="report_status" class="block text-sm font-medium text-gray-700 mb-2">Report Status</label>
                            <select name="report_status" id="report_status"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 workflow-select">
                                @foreach(['pending', 'completed'] as $reportStatus)
                                    <option value="{{ $reportStatus }}" 
                                        {{ ($order->report_status ?? 'pending') == $reportStatus ? 'selected' : '' }}
                                        {{ !in_array($reportStatus, $workflowInfo['report_statuses']) ? 'disabled' : '' }}>
                                        {{ ucfirst($reportStatus) }}
                                        @if(!in_array($reportStatus, $workflowInfo['report_statuses']) && ($order->report_status ?? 'pending') != $reportStatus)
                                            (Not Available)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Allowed: {{ implode(', ', array_map('ucfirst', $workflowInfo['report_statuses'])) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="button-text">Update Workflow</span>
                        </button>
                    </div>
                </form>
                </details>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const statusSelect = document.getElementById('status');
                        const sampleStatusSelect = document.getElementById('sample_status');
                        const reportStatusSelect = document.getElementById('report_status');
                        const form = document.getElementById('workflowForm');

                        // Prevent form submission if disabled options are selected
                        form.addEventListener('submit', function(e) {
                            const status = statusSelect.value;
                            const sampleStatus = sampleStatusSelect.value;
                            const reportStatus = reportStatusSelect.value;

                            const statusOption = statusSelect.options[statusSelect.selectedIndex];
                            const sampleOption = sampleStatusSelect.options[sampleStatusSelect.selectedIndex];
                            const reportOption = reportStatusSelect.options[reportStatusSelect.selectedIndex];

                            if (statusOption.disabled || sampleOption.disabled || reportOption.disabled) {
                                e.preventDefault();
                                alert('Please select only allowed workflow states. You cannot skip steps.');
                                return false;
                            }
                        });
                    });
                </script>
            </x-detail-card>

            <!-- Report Section -->
            @if(!$order->report)
            <x-detail-card 
                title="Upload Report" 
                gradient="from-emerald-500 to-teal-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </x-slot>
                <p class="text-gray-600 mb-4">Upload the diagnostic report for this test order.</p>
                <a href="{{ route('reports.create', $order) }}" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Report
                </a>
            </x-detail-card>
            @else
            <x-detail-card 
                title="Report Information" 
                gradient="from-green-500 to-emerald-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Report Name</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->report->report_name ?? 'N/A' }}</p>
                        @if($order->report->uploadedBy)
                            <p class="text-sm text-gray-500 mt-1">Uploaded by: {{ $order->report->uploadedBy->name }}</p>
                        @endif
                    </div>
                    <a href="{{ route('reports.show', $order->report) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        View Report
                    </a>
                </div>
            </x-detail-card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Patient Quick Info -->
            <x-detail-card 
                title="Patient Information" 
                gradient="from-purple-500 to-pink-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </x-slot>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Name</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->patient->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Phone</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->patient->phone }}</p>
                    </div>
                    <a href="{{ route('patients.show', $order->patient) }}" class="block w-full text-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors text-sm font-medium">
                        View Patient Profile
                    </a>
                </div>
            </x-detail-card>

            <!-- Quick Actions -->
            <x-detail-card 
                title="Quick Actions" 
                gradient="from-orange-500 to-red-600"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </x-slot>
                <div class="space-y-2">
                    @if($order->status === 'processing' || $order->status === 'sample_collected')
                    <a href="{{ route('orders.enter-results', $order) }}" class="block w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Enter Results</span>
                        </div>
                    </a>
                    @elseif($order->status === 'pending')
                    <div class="block w-full text-left px-4 py-3 bg-gray-50 text-gray-400 rounded-lg cursor-not-allowed">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Enter Results (Sample must be collected first)</span>
                        </div>
                    </div>
                    @endif
                    <a href="{{ route('patients.show', $order->patient) }}" class="block w-full text-left px-4 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-sm">View Patient</span>
                        </div>
                    </a>
                    @if($order->report)
                        @if($order->report->status === 'ready')
                        <a href="{{ route('reports.download', $order->report) }}" class="block w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <span class="font-medium text-sm">Download Report</span>
                            </div>
                        </a>
                        @endif
                        @if($order->status === 'completed' && $order->report_status === 'pending')
                        <form method="POST" action="{{ route('orders.update-status', $order) }}" class="block">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="report_status" value="completed">
                            <button type="submit" class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium text-sm">Mark Report as Completed</span>
                                </div>
                            </button>
                        </form>
                        @endif
                    @elseif($order->status === 'completed' && !$order->report)
                    <a href="{{ route('reports.create', $order) }}" class="block w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Create Report</span>
                        </div>
                    </a>
                    @endif
                </div>
            </x-detail-card>
        </div>
    </div>
</x-app-layout>
