<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Report Details</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Report #{{ $report->report_number ?? str_pad($report->id, 6, '0', STR_PAD_LEFT) }}
                    • Created {{ $report->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('reports.download', $report) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Report Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-lg font-semibold text-white">Report Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Patient Name</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $report->patient->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $report->patient->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Report Number</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $report->report_number ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Report Name</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $report->report_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $report->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Workflow Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $report->workflow_status === 'released' ? 'bg-green-100 text-green-800' : 
                                       ($report->workflow_status === 'reviewed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($report->workflow_status ?? 'draft') }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $report->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        @if($report->reviewed_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Reviewed At</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ $report->reviewed_at->format('M d, Y H:i') }}
                                @if($report->reviewedBy)
                                    <span class="text-xs text-gray-500">by {{ $report->reviewedBy->name }}</span>
                                @endif
                            </dd>
                        </div>
                        @endif
                        @if($report->released_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Released At</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ $report->released_at->format('M d, Y H:i') }}
                                @if($report->releasedBy)
                                    <span class="text-xs text-gray-500">by {{ $report->releasedBy->name }}</span>
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Tests in Report -->
            @if($report->reportTests && $report->reportTests->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600">
                    <h3 class="text-lg font-semibold text-white">Tests Included ({{ $report->reportTests->count() }})</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($report->reportTests as $reportTest)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">{{ $reportTest->test->name }}</h4>
                                @if($reportTest->testOrder && $reportTest->testOrder->testResults && $reportTest->testOrder->testResults->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference Range</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($reportTest->testOrder->testResults as $result)
                                                    <tr class="{{ $result->status === 'critical' ? 'bg-red-50' : ($result->status === 'high' || $result->status === 'low' ? 'bg-yellow-50' : '') }}">
                                                        <td class="px-3 py-2 text-sm font-medium text-gray-900">{{ $result->parameter_name }}</td>
                                                        <td class="px-3 py-2 text-sm text-gray-900">
                                                            {{ $result->result_value ?? 'N/A' }}
                                                            @if($result->status_flag)
                                                                <span class="ml-1 text-xs font-bold 
                                                                    {{ $result->status === 'critical' ? 'text-red-600' : 
                                                                       ($result->status === 'high' ? 'text-orange-600' : 'text-blue-600') }}">
                                                                    {{ $result->status_flag }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 text-sm text-gray-600">{{ $result->unit ?? '-' }}</td>
                                                        <td class="px-3 py-2 text-sm text-gray-600">{{ $result->reference_range ?? '-' }}</td>
                                                        <td class="px-3 py-2 text-sm">
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                                {{ $result->status === 'normal' ? 'bg-green-100 text-green-800' : 
                                                                   ($result->status === 'critical' ? 'bg-red-100 text-red-800' : 
                                                                   ($result->status === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800')) }}">
                                                                {{ ucfirst($result->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No results available for this test.</p>
                                @endif
                                @if($reportTest->interpretation)
                                    <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-400 rounded">
                                        <p class="text-sm font-medium text-blue-900">Interpretation:</p>
                                        <p class="text-sm text-blue-800 mt-1">{{ $reportTest->interpretation }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @elseif($report->testOrder)
            <!-- Single Test Results -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600">
                    <h3 class="text-lg font-semibold text-white">Test Results</h3>
                </div>
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">{{ $report->testOrder->test->name }}</h4>
                    @if($report->testOrder->testResults && $report->testOrder->testResults->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference Range</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($report->testOrder->testResults as $result)
                                        <tr class="{{ $result->status === 'critical' ? 'bg-red-50' : ($result->status === 'high' || $result->status === 'low' ? 'bg-yellow-50' : '') }}">
                                            <td class="px-3 py-2 text-sm font-medium text-gray-900">{{ $result->parameter_name }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-600">{{ $result->method_name ?? ($result->testParameter->method_name ?? 'Standard') }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $result->result_value ?? 'N/A' }}
                                                @if($result->status_flag)
                                                    <span class="ml-1 text-xs font-bold 
                                                        {{ $result->status === 'critical' ? 'text-red-600' : 
                                                           ($result->status === 'high' ? 'text-orange-600' : 'text-blue-600') }}">
                                                        {{ $result->status_flag }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-600">{{ $result->unit ?? '-' }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-600">{{ $result->reference_range ?? '-' }}</td>
                                            <td class="px-3 py-2 text-sm">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $result->status === 'normal' ? 'bg-green-100 text-green-800' : 
                                                       ($result->status === 'critical' ? 'bg-red-100 text-red-800' : 
                                                       ($result->status === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800')) }}">
                                                    {{ ucfirst($result->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No results available.</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Interpretation -->
            @if($report->interpretation)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600">
                    <h3 class="text-lg font-semibold text-white">Interpretation</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $report->interpretation }}</p>
                </div>
            </div>
            @endif

            <!-- Remarks -->
            @if($report->remarks)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
                    <h3 class="text-lg font-semibold text-white">Remarks</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-900">{{ $report->remarks }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Workflow Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600">
                    <h3 class="text-lg font-semibold text-white">Workflow Management</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('reports.update-workflow', $report) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="workflow_status" class="block text-sm font-medium text-gray-700 mb-2">Workflow Status</label>
                            <select name="workflow_status" id="workflow_status" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                <option value="draft" {{ $report->workflow_status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="reviewed" {{ $report->workflow_status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="released" {{ $report->workflow_status === 'released' ? 'selected' : '' }}>Released</option>
                            </select>
                        </div>

                        <div>
                            <label for="interpretation" class="block text-sm font-medium text-gray-700 mb-2">Interpretation</label>
                            <textarea name="interpretation" id="interpretation" rows="4"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                                placeholder="Add interpretation notes...">{{ old('interpretation', $report->interpretation) }}</textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                            Update Workflow
                        </button>
                    </form>
                </div>
            </div>

            <!-- Doctor Signature -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-600">
                    <h3 class="text-lg font-semibold text-white">Doctor Signature</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('reports.update-doctor-signature', $report) }}" enctype="multipart/form-data" class="space-y-4" id="doctorSignatureForm">
                        @csrf
                        @method('PUT')
                        
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div>
                            <label for="doctor_name" class="block text-sm font-medium text-gray-700 mb-2">Doctor Name *</label>
                            <input type="text" name="doctor_name" id="doctor_name" required
                                value="{{ old('doctor_name', $report->doctor_name) }}"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 @error('doctor_name') border-red-500 @enderror">
                            @error('doctor_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="doctor_qualification" class="block text-sm font-medium text-gray-700 mb-2">Qualification *</label>
                            <input type="text" name="doctor_qualification" id="doctor_qualification" required
                                value="{{ old('doctor_qualification', $report->doctor_qualification) }}"
                                placeholder="e.g., M.D. Pathology"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 @error('doctor_qualification') border-red-500 @enderror">
                            @error('doctor_qualification')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="doctor_signature" class="block text-sm font-medium text-gray-700 mb-2">Signature Image</label>
                            <input type="file" name="doctor_signature" id="doctor_signature" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('doctor_signature') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Accepted formats: JPEG, JPG, PNG. Max size: 2MB</p>
                            @error('doctor_signature')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($report->doctor_signature)
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500 mb-1">Current signature:</p>
                                    <img src="{{ asset('storage/' . $report->doctor_signature) }}" alt="Doctor Signature" class="h-20 border border-gray-200 rounded" onerror="this.style.display='none'">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                            <span id="submitText">Update Signature</span>
                            <span id="submitLoading" class="hidden">Saving...</span>
                        </button>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('doctorSignatureForm');
                            if (form) {
                                form.addEventListener('submit', function(e) {
                                    const submitBtn = this.querySelector('button[type="submit"]');
                                    const submitText = document.getElementById('submitText');
                                    const submitLoading = document.getElementById('submitLoading');
                                    
                                    // Validate required fields before submitting
                                    const doctorName = document.getElementById('doctor_name').value.trim();
                                    const doctorQualification = document.getElementById('doctor_qualification').value.trim();
                                    
                                    if (!doctorName || !doctorQualification) {
                                        e.preventDefault();
                                        alert('Please fill in all required fields (Doctor Name and Qualification).');
                                        return false;
                                    }
                                    
                                    // Show loading state
                                    if (submitText) submitText.classList.add('hidden');
                                    if (submitLoading) submitLoading.classList.remove('hidden');
                                    if (submitBtn) submitBtn.disabled = true;
                                    
                                    // Allow form to submit normally
                                    return true;
                                });
                            }
                        });
                    </script>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-500 to-gray-600">
                    <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-2">
                    <a href="{{ route('reports.download', $report) }}" class="block w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span class="font-medium text-sm">Download PDF</span>
                        </div>
                    </a>
                    <a href="{{ route('patients.show', $report->patient) }}" class="block w-full text-left px-4 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-sm">View Patient</span>
                        </div>
                    </a>
                    @if($report->testOrder)
                    <a href="{{ route('orders.show', $report->testOrder) }}" class="block w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">View Test Order</span>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
