<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $reportTemplate->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Manage template sections and configuration</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('report-templates.edit', $reportTemplate) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    Edit Template
                </a>
                <a href="{{ route('report-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    ← Back
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Template Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Template Information</h3>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $reportTemplate->template_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Layout</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $reportTemplate->layout_type }}</dd>
                    </div>
                    @if($reportTemplate->description)
                    <div class="col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reportTemplate->description }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Sections -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Template Sections</h3>
                    <button type="button" onclick="document.getElementById('addSectionModal').classList.remove('hidden')" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Section
                    </button>
                </div>

                @if($reportTemplate->sections->count() > 0)
                    <div id="sectionsList" class="space-y-3">
                        @foreach($reportTemplate->sections->sortBy('sort_order') as $section)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors" data-section-id="{{ $section->id }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="px-2 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded capitalize">{{ $section->section_type }}</span>
                                            @if($section->title)
                                                <h4 class="font-medium text-gray-900">{{ $section->title }}</h4>
                                            @endif
                                            @if(!$section->is_visible)
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Hidden</span>
                                            @endif
                                        </div>
                                        @if($section->content_json)
                                            <p class="text-sm text-gray-600">{{ json_encode($section->content_json, JSON_PRETTY_PRINT) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 ml-4">
                                        <button type="button" onclick="editSection({{ $section->id }})" 
                                            class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('report-templates.sections.destroy', [$reportTemplate, $section]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this section?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 mb-4">No sections added yet. Add your first section to start building the template.</p>
                        <button type="button" onclick="document.getElementById('addSectionModal').classList.remove('hidden')" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            Add First Section
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Template Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Template Stats</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sections</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $reportTemplate->sections->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Used by Tests</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $reportTemplate->tests->count() }}</dd>
                    </div>
                    @if($reportTemplate->is_default)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-sm font-semibold bg-indigo-100 text-indigo-800 rounded-full">Default Template</span>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Assigned Tests -->
            @if($reportTemplate->tests->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Tests</h3>
                <ul class="space-y-2">
                    @foreach($reportTemplate->tests as $test)
                        <li class="text-sm text-gray-700">
                            <a href="{{ route('tests.edit', $test) }}" class="hover:text-indigo-600">{{ $test->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Add Section Modal -->
    <div id="addSectionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Section</h3>
                <form method="POST" action="{{ route('report-templates.sections.store', $reportTemplate) }}" id="addSectionForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="section_type" class="block text-sm font-medium text-gray-700 mb-2">Section Type *</label>
                            <select name="section_type" id="section_type" required onchange="updateSectionForm()"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                <option value="parameters">Parameters - Display test results table</option>
                                <option value="text">Text - Static or dynamic text content</option>
                                <option value="table">Table - Custom data table</option>
                                <option value="calculated">Calculated - Formula-based values</option>
                                <option value="interpretation">Interpretation - Test interpretation notes</option>
                                <option value="notes">Notes - Footer notes or disclaimers</option>
                            </select>
                        </div>
                        <div>
                            <label for="section_title" class="block text-sm font-medium text-gray-700 mb-2">Title (Optional)</label>
                            <input type="text" name="title" id="section_title"
                                placeholder="e.g., Test Results, Interpretation"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        </div>
                        
                        <!-- Dynamic Content Fields Based on Section Type -->
                        <div id="sectionContentFields">
                            <!-- Parameters: No additional fields needed -->
                            <div id="content_parameters" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm text-blue-800">This section will automatically display all test parameters in a table format.</p>
                                </div>
                            </div>
                            
                            <!-- Text Section -->
                            <div id="content_text" class="hidden">
                                <label for="text_content" class="block text-sm font-medium text-gray-700 mb-2">Text Content *</label>
                                <textarea name="text_content" id="text_content" rows="5"
                                    placeholder="Enter the text content to display in the report..."
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"></textarea>
                            </div>
                            
                            <!-- Calculated Section -->
                            <div id="content_calculated" class="hidden">
                                <div class="space-y-3">
                                    <div>
                                        <label for="calculated_formula" class="block text-sm font-medium text-gray-700 mb-2">Formula *</label>
                                        <input type="text" name="calculated_formula" id="calculated_formula"
                                            placeholder="e.g., eAG = (28.7 × HbA1c) − 46.7"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 font-mono text-sm">
                                        <p class="mt-1 text-xs text-gray-500">Use parameter names from test results. Example: HbA1c, Glucose, etc.</p>
                                    </div>
                                    <div>
                                        <label for="calculated_unit" class="block text-sm font-medium text-gray-700 mb-2">Unit (Optional)</label>
                                        <input type="text" name="calculated_unit" id="calculated_unit"
                                            placeholder="e.g., mg/dL, mmol/L"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Interpretation Section -->
                            <div id="content_interpretation" class="hidden">
                                <div class="space-y-3">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3">
                                        <p class="text-sm text-yellow-800 mb-2"><strong>Interpretation Rules:</strong> Define conditions and interpretation text.</p>
                                        <p class="text-xs text-yellow-700">Example: If HbA1c > 6.5, show "Diabetes"</p>
                                    </div>
                                    <div id="interpretation_rules">
                                        <div class="interpretation-rule border border-gray-200 rounded-lg p-4 mb-3">
                                            <div class="grid grid-cols-3 gap-3 mb-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Parameter</label>
                                                    <input type="text" name="interpretation_parameter[]" placeholder="e.g., HbA1c"
                                                        class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Condition</label>
                                                    <select name="interpretation_condition[]" class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                                                        <option value="gt">Greater Than (>)</option>
                                                        <option value="lt">Less Than (<)</option>
                                                        <option value="eq">Equal To (=)</option>
                                                        <option value="between">Between</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Value</label>
                                                    <input type="text" name="interpretation_value[]" placeholder="6.5"
                                                        class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Interpretation Text</label>
                                                <textarea name="interpretation_text[]" rows="2" placeholder="e.g., Diabetes"
                                                    class="block w-full rounded border-gray-300 text-sm px-3 py-2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="addInterpretationRule()" class="text-sm text-indigo-600 hover:text-indigo-800">
                                        + Add Another Rule
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Table Section -->
                            <div id="content_table" class="hidden">
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Table Headers (comma-separated)</label>
                                        <input type="text" name="table_headers" id="table_headers"
                                            placeholder="e.g., Category, Normal Range, Notes"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Table Rows (one per line, comma-separated values)</label>
                                        <textarea name="table_rows" id="table_rows" rows="5"
                                            placeholder="e.g.,&#10;Normal, 4.0-5.6, Within normal range&#10;Prediabetes, 5.7-6.4, Increased risk&#10;Diabetes, ≥6.5, Diabetes diagnosis"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 font-mono text-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div id="content_notes" class="hidden">
                                <label for="notes_content" class="block text-sm font-medium text-gray-700 mb-2">Notes Content *</label>
                                <textarea name="notes_content" id="notes_content" rows="4"
                                    placeholder="Enter notes, disclaimers, or footer text..."
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"></textarea>
                            </div>
                        </div>
                        
                        <div>
                            <label for="section_sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number" name="sort_order" id="section_sort_order" value="{{ ($reportTemplate->sections->max('sort_order') ?? -1) + 1 }}" min="0"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeAddModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                            Add Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div id="editSectionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Section</h3>
                <form method="POST" id="editSectionForm">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_section_type" class="block text-sm font-medium text-gray-700 mb-2">Section Type *</label>
                            <select name="section_type" id="edit_section_type" required onchange="updateEditSectionFormFromType()"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                <option value="parameters">Parameters</option>
                                <option value="text">Text</option>
                                <option value="table">Table</option>
                                <option value="calculated">Calculated</option>
                                <option value="interpretation">Interpretation</option>
                                <option value="notes">Notes</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_section_title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="title" id="edit_section_title"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        </div>
                        <div id="editSectionContentFields">
                            <!-- Content fields will be populated dynamically -->
                        </div>
                        <div>
                            <label for="edit_section_sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number" name="sort_order" id="edit_section_sort_order" min="0"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_visible" id="edit_section_is_visible" value="1"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="edit_section_is_visible" class="ml-2 block text-sm text-gray-700">
                                Visible in report
                            </label>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                            Update Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let interpretationRuleCount = 1;
        
        function updateSectionForm() {
            const sectionType = document.getElementById('section_type').value;
            // Hide all content fields
            ['parameters', 'text', 'calculated', 'interpretation', 'table', 'notes'].forEach(type => {
                document.getElementById('content_' + type).classList.add('hidden');
            });
            // Show relevant content field
            document.getElementById('content_' + sectionType).classList.remove('hidden');
        }
        
        function addInterpretationRule() {
            interpretationRuleCount++;
            const rulesContainer = document.getElementById('interpretation_rules');
            const newRule = document.createElement('div');
            newRule.className = 'interpretation-rule border border-gray-200 rounded-lg p-4 mb-3';
            newRule.innerHTML = `
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Parameter</label>
                        <input type="text" name="interpretation_parameter[]" placeholder="e.g., HbA1c"
                            class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Condition</label>
                        <select name="interpretation_condition[]" class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                            <option value="gt">Greater Than (>)</option>
                            <option value="lt">Less Than (<)</option>
                            <option value="eq">Equal To (=)</option>
                            <option value="between">Between</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Value</label>
                        <input type="text" name="interpretation_value[]" placeholder="6.5"
                            class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Interpretation Text</label>
                    <textarea name="interpretation_text[]" rows="2" placeholder="e.g., Diabetes"
                        class="block w-full rounded border-gray-300 text-sm px-3 py-2"></textarea>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="mt-2 text-xs text-red-600 hover:text-red-800">Remove Rule</button>
            `;
            rulesContainer.appendChild(newRule);
        }
        
        function closeAddModal() {
            document.getElementById('addSectionModal').classList.add('hidden');
            document.getElementById('addSectionForm').reset();
            updateSectionForm();
        }
        
        function closeEditModal() {
            document.getElementById('editSectionModal').classList.add('hidden');
        }
        
        function editSection(sectionId) {
            // Fetch section data and populate edit form
            const url = '{{ route("report-templates.sections.show", [$reportTemplate, ":section"]) }}'.replace(':section', sectionId);
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    document.getElementById('edit_section_type').value = data.section_type;
                    document.getElementById('edit_section_title').value = data.title || '';
                    document.getElementById('edit_section_sort_order').value = data.sort_order;
                    document.getElementById('edit_section_is_visible').checked = data.is_visible;
                    
                    // Set form action
                    const updateUrl = '{{ route("report-templates.sections.update", [$reportTemplate, ":section"]) }}'.replace(':section', sectionId);
                    document.getElementById('editSectionForm').action = updateUrl;
                    
                    // Populate content based on section type
                    updateEditSectionForm(data);
                    
                    document.getElementById('editSectionModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load section data. Please refresh the page and try again.');
                });
        }
        
        function updateEditSectionForm(sectionData) {
            const sectionType = document.getElementById('edit_section_type').value;
            const contentFields = document.getElementById('editSectionContentFields');
            const content = sectionData?.content_json || {};
            
            // Clear existing content
            contentFields.innerHTML = '';
            
            // Escape HTML for safe display
            const escapeHtml = (text) => {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };
            
            switch(sectionType) {
                case 'text':
                    contentFields.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-2">Text Content</label>
                        <textarea name="text_content" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">${escapeHtml(content.text || '')}</textarea>
                    `;
                    break;
                case 'calculated':
                    contentFields.innerHTML = `
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                                <input type="text" name="calculated_formula" value="${escapeHtml(content.formula || '')}" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" name="calculated_unit" value="${escapeHtml(content.unit || '')}" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            </div>
                        </div>
                    `;
                    break;
                case 'notes':
                    contentFields.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes Content</label>
                        <textarea name="notes_content" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">${escapeHtml(content.notes || '')}</textarea>
                    `;
                    break;
                case 'table':
                    const headers = content.headers ? content.headers.join(', ') : '';
                    const rows = content.rows ? content.rows.map(r => Array.isArray(r) ? r.join(', ') : r).join('\n') : '';
                    contentFields.innerHTML = `
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Headers (comma-separated)</label>
                                <input type="text" name="table_headers" value="${escapeHtml(headers)}" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Rows (one per line, comma-separated)</label>
                                <textarea name="table_rows" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 font-mono text-sm">${escapeHtml(rows)}</textarea>
                            </div>
                        </div>
                    `;
                    break;
                case 'interpretation':
                    let rulesHtml = '<div class="space-y-3"><div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3"><p class="text-sm text-yellow-800">Interpretation Rules</p></div><div id="edit_interpretation_rules">';
                    if (content.rules && content.rules.length > 0) {
                        content.rules.forEach((rule) => {
                            const value = rule.condition === 'between' && typeof rule.value === 'object' 
                                ? `${rule.value.min}-${rule.value.max}` 
                                : (rule.value || '');
                            rulesHtml += `
                                <div class="interpretation-rule border border-gray-200 rounded-lg p-4 mb-3">
                                    <div class="grid grid-cols-3 gap-3 mb-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Parameter</label>
                                            <input type="text" name="interpretation_parameter[]" value="${escapeHtml(rule.parameter || '')}" 
                                                class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Condition</label>
                                            <select name="interpretation_condition[]" class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                                                <option value="gt" ${rule.condition === 'gt' ? 'selected' : ''}>Greater Than (>)</option>
                                                <option value="lt" ${rule.condition === 'lt' ? 'selected' : ''}>Less Than (<)</option>
                                                <option value="eq" ${rule.condition === 'eq' ? 'selected' : ''}>Equal To (=)</option>
                                                <option value="between" ${rule.condition === 'between' ? 'selected' : ''}>Between</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Value</label>
                                            <input type="text" name="interpretation_value[]" value="${escapeHtml(value)}" 
                                                class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Interpretation Text</label>
                                        <textarea name="interpretation_text[]" rows="2" 
                                            class="block w-full rounded border-gray-300 text-sm px-3 py-2">${escapeHtml(rule.interpretation || '')}</textarea>
                                    </div>
                                    <button type="button" onclick="this.parentElement.remove()" class="mt-2 text-xs text-red-600 hover:text-red-800">Remove Rule</button>
                                </div>
                            `;
                        });
                    } else {
                        rulesHtml += addInterpretationRuleHtml();
                    }
                    rulesHtml += '</div><button type="button" onclick="addEditInterpretationRule()" class="text-sm text-indigo-600 hover:text-indigo-800">+ Add Another Rule</button></div>';
                    contentFields.innerHTML = rulesHtml;
                    break;
                default:
                    contentFields.innerHTML = '<p class="text-sm text-gray-500">No additional configuration needed for this section type.</p>';
            }
        }
        
        function addEditInterpretationRule() {
            const rulesContainer = document.getElementById('edit_interpretation_rules');
            if (!rulesContainer) return;
            const newRule = document.createElement('div');
            newRule.className = 'interpretation-rule border border-gray-200 rounded-lg p-4 mb-3';
            newRule.innerHTML = addInterpretationRuleHtml() + '<button type="button" onclick="this.parentElement.remove()" class="mt-2 text-xs text-red-600 hover:text-red-800">Remove Rule</button>';
            rulesContainer.appendChild(newRule);
        }
        
        function addInterpretationRuleHtml() {
            return `
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Parameter</label>
                        <input type="text" name="interpretation_parameter[]" placeholder="e.g., HbA1c"
                            class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Condition</label>
                        <select name="interpretation_condition[]" class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                            <option value="gt">Greater Than (>)</option>
                            <option value="lt">Less Than (<)</option>
                            <option value="eq">Equal To (=)</option>
                            <option value="between">Between</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Value</label>
                        <input type="text" name="interpretation_value[]" placeholder="6.5"
                            class="block w-full rounded border-gray-300 text-sm px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Interpretation Text</label>
                    <textarea name="interpretation_text[]" rows="2" placeholder="e.g., Diabetes"
                        class="block w-full rounded border-gray-300 text-sm px-3 py-2"></textarea>
                </div>
            `;
        }
        
        function updateEditSectionFormFromType() {
            const sectionType = document.getElementById('edit_section_type').value;
            const contentFields = document.getElementById('editSectionContentFields');
            
            // Clear existing content
            contentFields.innerHTML = '';
            
            // Show appropriate fields based on type
            switch(sectionType) {
                case 'text':
                    contentFields.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-2">Text Content</label>
                        <textarea name="text_content" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"></textarea>
                    `;
                    break;
                case 'calculated':
                    contentFields.innerHTML = `
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                                <input type="text" name="calculated_formula" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" name="calculated_unit" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            </div>
                        </div>
                    `;
                    break;
                case 'notes':
                    contentFields.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes Content</label>
                        <textarea name="notes_content" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"></textarea>
                    `;
                    break;
                case 'table':
                    contentFields.innerHTML = `
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Headers (comma-separated)</label>
                                <input type="text" name="table_headers" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Rows (one per line, comma-separated)</label>
                                <textarea name="table_rows" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 font-mono text-sm"></textarea>
                            </div>
                        </div>
                    `;
                    break;
                case 'interpretation':
                    contentFields.innerHTML = `
                        <div class="space-y-3">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3">
                                <p class="text-sm text-yellow-800">Interpretation Rules</p>
                            </div>
                            <div id="edit_interpretation_rules">
                                ${addInterpretationRuleHtml()}
                            </div>
                            <button type="button" onclick="addEditInterpretationRule()" class="text-sm text-indigo-600 hover:text-indigo-800">+ Add Another Rule</button>
                        </div>
                    `;
                    break;
                default:
                    contentFields.innerHTML = '<p class="text-sm text-gray-500">No additional configuration needed for this section type.</p>';
            }
        }
        
        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSectionForm();
        });
    </script>
</x-app-layout>
