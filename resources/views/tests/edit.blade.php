<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Test</h2>
                <p class="text-sm text-gray-500 mt-1">Update test information</p>
            </div>
            <a href="{{ route('tests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
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

    <div class="space-y-6">
        <!-- Test Information -->
        <form method="POST" action="{{ route('tests.update', $test) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <x-form-section title="Test Information" description="Update the test details and pricing">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-input 
                        label="Test Name" 
                        name="name" 
                        type="text" 
                        required 
                        placeholder="e.g., Complete Blood Count (CBC)"
                        value="{{ old('name', $test->name) }}"
                    />

                    <x-form-input 
                        label="Price (₹)" 
                        name="price" 
                        type="number" 
                        required 
                        placeholder="0.00"
                        value="{{ old('price', $test->price) }}"
                        step="0.01"
                        min="0"
                    />
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service image (optional)</label>
                    @if($test->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $test->image) }}" alt="{{ $test->name }}" class="h-24 w-auto rounded-lg border border-gray-200 object-cover">
                            <p class="mt-1 text-xs text-gray-500">Current image. Upload a new file to replace.</p>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Shown on the public "Our Services" page. Max 2MB.</p>
                </div>

                <div class="mt-6">
                    <x-form-textarea 
                        label="Description" 
                        name="description" 
                        rows="4"
                        placeholder="Enter test description, preparation instructions, or other relevant information. Shown on the public website."
                        value="{{ old('description', $test->description) }}"
                    />
                </div>

                <div class="mt-6">
                    <label for="report_template_id" class="block text-sm font-medium text-gray-700 mb-2">Report Template</label>
                    <select name="report_template_id" id="report_template_id"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5">
                        <option value="">Use Default Template</option>
                        @foreach(\App\Models\DiagnosticReportTemplate::forCurrentLab()->orderBy('name')->get() as $template)
                            <option value="{{ $template->id }}" {{ old('report_template_id', $test->report_template_id) == $template->id ? 'selected' : '' }}>
                                {{ $template->name }} @if($template->is_default) (Default) @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Select a custom report template for this test. If not selected, the default template will be used.</p>
                </div>

                <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4 space-y-3">
                    <input type="hidden" name="featured_on_homepage" value="0">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-800">
                        <input type="checkbox" name="featured_on_homepage" value="1" {{ old('featured_on_homepage', $test->featured_on_homepage) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                        Feature on public homepage (“Our Services”)
                    </label>
                    <p class="text-xs text-gray-500">If any test is featured, only featured tests appear in the carousel (up to 24). Otherwise the latest tests are shown.</p>
                    <div>
                        <label for="homepage_sort_order" class="block text-xs font-medium text-gray-600 mb-1">Homepage sort (lower first)</label>
                        <input type="number" name="homepage_sort_order" id="homepage_sort_order" min="0" max="999999" step="1" value="{{ old('homepage_sort_order', $test->homepage_sort_order) }}" placeholder="Optional" class="block w-full max-w-xs rounded-lg border-gray-300 text-sm py-2">
                    </div>
                </div>
            </x-form-section>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <a href="{{ route('tests.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Test
                </button>
            </div>
        </form>

        <!-- Test Parameters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">Test Parameters</h3>
                    <p class="text-sm text-blue-100 mt-1">Define parameters that will be automatically loaded when this test is ordered</p>
                </div>
                <button onclick="document.getElementById('add-parameter-form').classList.toggle('hidden')" 
                        class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-1 px-4 rounded-lg transition-all">
                    + Add Parameter
                </button>
            </div>
            <div class="p-6">
                <!-- Add Parameter Form -->
                <div id="add-parameter-form" class="hidden mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <form method="POST" action="{{ route('tests.parameters.store', $test) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="parameter_name" class="block text-sm font-medium text-gray-700 mb-2">Parameter Name *</label>
                                <input type="text" name="parameter_name" id="parameter_name" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                                    placeholder="e.g., Hemoglobin">
                            </div>
                            <div>
                                <label for="method_name" class="block text-sm font-medium text-gray-700 mb-2">Method Name</label>
                                <input type="text" name="method_name" id="method_name"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                                    placeholder="e.g., Automated Analyzer">
                            </div>
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" name="unit" id="unit"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                                    placeholder="e.g., g/dL">
                            </div>
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" min="0"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                                    placeholder="0">
                            </div>
                            <div>
                                <label for="reference_range_low" class="block text-sm font-medium text-gray-700 mb-2">Reference Range (Low)</label>
                                <input type="number" name="reference_range_low" id="reference_range_low" step="0.01"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                                    placeholder="e.g., 12.0">
                            </div>
                            <div>
                                <label for="reference_range_high" class="block text-sm font-medium text-gray-700 mb-2">Reference Range (High)</label>
                                <input type="number" name="reference_range_high" id="reference_range_high" step="0.01"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                                    placeholder="e.g., 16.0">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                                Add Parameter
                            </button>
                            <button type="button" onclick="document.getElementById('add-parameter-form').classList.add('hidden')" 
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Parameters List -->
                @if($test->parameters && $test->parameters->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Range</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($test->parameters->sortBy('sort_order') as $parameter)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $parameter->parameter_name }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ $parameter->method_name ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ $parameter->unit ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ $parameter->formatted_reference_range }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ $parameter->sort_order }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editParameter({{ $parameter->id }})" 
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <form method="POST" action="{{ route('tests.parameters.destroy', [$test, $parameter]) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this parameter?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm">No parameters defined yet. Add parameters to automatically load them when this test is ordered.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Parameter Modal -->
    <div id="edit-parameter-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Parameter</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="edit-parameter-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_parameter_name" class="block text-sm font-medium text-gray-700 mb-2">Parameter Name *</label>
                        <input type="text" name="parameter_name" id="edit_parameter_name" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="edit_method_name" class="block text-sm font-medium text-gray-700 mb-2">Method Name</label>
                        <input type="text" name="method_name" id="edit_method_name"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="edit_unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                        <input type="text" name="unit" id="edit_unit"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_sort_order" min="0"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="edit_reference_range_low" class="block text-sm font-medium text-gray-700 mb-2">Reference Range (Low)</label>
                        <input type="number" name="reference_range_low" id="edit_reference_range_low" step="0.01"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                    <div>
                        <label for="edit_reference_range_high" class="block text-sm font-medium text-gray-700 mb-2">Reference Range (High)</label>
                        <input type="number" name="reference_range_high" id="edit_reference_range_high" step="0.01"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        Update Parameter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const parameters = @json($test->parameters);
        
        function editParameter(parameterId) {
            const parameter = parameters.find(p => p.id === parameterId);
            if (!parameter) return;
            
            document.getElementById('edit_parameter_name').value = parameter.parameter_name || '';
            document.getElementById('edit_method_name').value = parameter.method_name || '';
            document.getElementById('edit_unit').value = parameter.unit || '';
            document.getElementById('edit_sort_order').value = parameter.sort_order || 0;
            document.getElementById('edit_reference_range_low').value = parameter.reference_range_low || '';
            document.getElementById('edit_reference_range_high').value = parameter.reference_range_high || '';
            
            document.getElementById('edit-parameter-form').action = '{{ route("tests.parameters.update", [$test, ":id"]) }}'.replace(':id', parameterId);
            document.getElementById('edit-parameter-modal').classList.remove('hidden');
        }
        
        function closeEditModal() {
            document.getElementById('edit-parameter-modal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>
