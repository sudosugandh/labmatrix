<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Report') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Order Information</h3>
                <p class="text-sm text-gray-600">Patient: {{ $order->patient->name }}</p>
                <p class="text-sm text-gray-600">Test: {{ $order->test->name }}</p>
            </div>

            <form method="POST" action="{{ route('reports.store', $order) }}" enctype="multipart/form-data" id="report-form">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="report_name" class="block text-sm font-medium text-gray-700">Report Name</label>
                        <input type="text" name="report_name" id="report_name" value="{{ old('report_name', $order->test->name . ' Report') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('report_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 bg-blue-50">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="generate_pdf" id="generate_pdf" value="1" 
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                onchange="toggleFileUpload()">
                            <label for="generate_pdf" class="ml-2 block text-sm font-medium text-gray-700">
                                Generate Professional PDF Report
                            </label>
                        </div>
                        <p class="text-xs text-gray-600 ml-6 mb-3">
                            Automatically generate a professional laboratory test report PDF with patient information, test results, and lab branding.
                        </p>
                        <div class="ml-6">
                            <a href="{{ route('reports.preview', $order) }}" target="_blank" 
                                class="inline-flex items-center px-3 py-2 border border-indigo-300 shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview PDF
                            </a>
                        </div>
                    </div>

                    <div id="file-upload-section">
                        <label for="report_file" class="block text-sm font-medium text-gray-700">Or Upload Report File (PDF)</label>
                        <input type="file" name="report_file" id="report_file" accept=".pdf"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('report_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="ready" {{ old('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-4">
                    <a href="{{ route('orders.show', $order) }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <span id="submit-text">Save Report</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFileUpload() {
            const generatePdf = document.getElementById('generate_pdf').checked;
            const fileUploadSection = document.getElementById('file-upload-section');
            const fileInput = document.getElementById('report_file');
            
            if (generatePdf) {
                fileUploadSection.style.display = 'none';
                fileInput.removeAttribute('required');
            } else {
                fileUploadSection.style.display = 'block';
                fileInput.setAttribute('required', 'required');
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleFileUpload();
        });
    </script>
</x-app-layout>
