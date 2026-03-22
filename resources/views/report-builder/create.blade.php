<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Report Builder</h2>
                <p class="text-sm text-gray-500 mt-1">Create custom reports with filters and formatting</p>
            </div>
            <a href="{{ route('report-builder.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                ← Back to Reports
            </a>
        </div>
    </x-slot>

    <form id="report-builder-form" method="POST" action="{{ route('report-builder.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Sidebar - Configuration -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Report Type & Basic Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Configuration</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Type *</label>
                            <select name="report_type" id="report-type" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="patients" {{ $reportType === 'patients' ? 'selected' : '' }}>Patients</option>
                                <option value="appointments" {{ $reportType === 'appointments' ? 'selected' : '' }}>Appointments</option>
                                <option value="orders" {{ $reportType === 'orders' ? 'selected' : '' }}>Test Orders</option>
                                <option value="bills" {{ $reportType === 'bills' ? 'selected' : '' }}>Bills</option>
                                <option value="doctors" {{ $reportType === 'doctors' ? 'selected' : '' }}>Doctors</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Name *</label>
                            <input type="text" name="name" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Monthly Patient Report">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional description"></textarea>
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_public" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Make public</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Set as default</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Quick Templates -->
                @if($templates->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Templates</h3>
                    <div class="space-y-2">
                        @foreach($templates as $template)
                            <button type="button" 
                                    onclick="loadTemplate({{ $template->id }})"
                                    class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50 border border-gray-200 transition-colors">
                                <div class="font-medium text-sm text-gray-900">{{ $template->name }}</div>
                                <div class="text-xs text-gray-500">{{ $template->usage_count }} uses</div>
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
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
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 field-checkbox">
                                <span class="ml-2 text-sm text-gray-700">{{ $config['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                        <button type="button" onclick="addFilter()" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Filter
                        </button>
                    </div>
                    
                    <div id="filters-container" class="space-y-3">
                        <!-- Filters will be added dynamically -->
                    </div>
                </div>

                <!-- Sorting -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Sorting</h3>
                        <button type="button" onclick="addSort()" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Sort
                        </button>
                    </div>
                    
                    <div id="sorting-container" class="space-y-3">
                        <!-- Sorting will be added dynamically -->
                    </div>
                </div>

                <!-- Grouping -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Grouping & Subtotals</h3>
                        <label class="flex items-center">
                            <input type="checkbox" id="enable-grouping" onchange="toggleGrouping()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Enable Grouping</span>
                        </label>
                    </div>
                    
                    <div id="grouping-container" class="space-y-3 hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Group By Fields</label>
                            <div id="group-fields-container" class="space-y-2">
                                <!-- Group fields will be added here -->
                            </div>
                            <button type="button" onclick="addGroupField()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                + Add Group Field
                            </button>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aggregations (Subtotals)</label>
                            <div id="aggregate-fields-container" class="space-y-2">
                                <!-- Aggregate fields will be added here -->
                            </div>
                            <button type="button" onclick="addAggregateField()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                + Add Aggregation
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formatting Options -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Formatting Options</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                            <select name="formatting[date_format]" class="block w-full rounded-lg border-gray-300 text-sm">
                                <option value="Y-m-d">YYYY-MM-DD (2026-03-14)</option>
                                <option value="d/m/Y">DD/MM/YYYY (14/03/2026)</option>
                                <option value="m/d/Y">MM/DD/YYYY (03/14/2026)</option>
                                <option value="d M Y">DD MMM YYYY (14 Mar 2026)</option>
                                <option value="F j, Y">Month Day, Year (March 14, 2026)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number Format</label>
                            <select name="formatting[number_format]" class="block w-full rounded-lg border-gray-300 text-sm">
                                <option value="0">No decimals (1000)</option>
                                <option value="2" selected>2 decimals (1,000.00)</option>
                                <option value="3">3 decimals (1,000.000)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol</label>
                            <input type="text" name="formatting[currency_symbol]" value="₹" class="block w-full rounded-lg border-gray-300 text-sm" placeholder="₹">
                        </div>
                    </div>
                </div>

                <!-- Chart Options -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Charts</h3>
                        <label class="flex items-center">
                            <input type="checkbox" id="enable-charts" onchange="toggleCharts()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Show Charts</span>
                        </label>
                    </div>
                    
                    <div id="charts-container" class="space-y-3 hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chart Type</label>
                            <select name="chart_type" id="chart-type" class="block w-full rounded-lg border-gray-300 text-sm">
                                <option value="">None</option>
                                <option value="bar">Bar Chart</option>
                                <option value="line">Line Chart</option>
                                <option value="pie">Pie Chart</option>
                                <option value="doughnut">Doughnut Chart</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chart Field (X-Axis)</label>
                            <select name="chart_field_x" id="chart-field-x" class="block w-full rounded-lg border-gray-300 text-sm">
                                <option value="">Select Field</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chart Value (Y-Axis)</label>
                            <select name="chart_field_y" id="chart-field-y" class="block w-full rounded-lg border-gray-300 text-sm">
                                <option value="">Select Field</option>
                            </select>
                        </div>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Save Template
                        </button>
                        <button type="button" onclick="exportReport('csv')" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Preview Modal -->
    <div id="preview-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Report Preview</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="preview-content" class="overflow-auto max-h-96">
                <!-- Preview content will be loaded here -->
            </div>
        </div>
    </div>
</x-app-layout>

<script>
const availableFields = @json($availableFields);
const availableFilters = @json($availableFilters);
let filterCount = 0;
let sortCount = 0;

// Change report type
document.getElementById('report-type')?.addEventListener('change', function() {
    window.location.href = '{{ route("report-builder.create") }}?type=' + this.value;
});

// Select/Deselect all fields
function selectAllFields() {
    document.querySelectorAll('.field-checkbox').forEach(cb => cb.checked = true);
}

function deselectAllFields() {
    document.querySelectorAll('.field-checkbox').forEach(cb => cb.checked = false);
}

// Add filter
function addFilter() {
    const container = document.getElementById('filters-container');
    const filterId = 'filter-' + filterCount++;
    
    const filterHtml = `
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg" id="${filterId}">
            <select name="filters[${filterCount}][field]" class="flex-1 rounded-lg border-gray-300 text-sm" onchange="updateFilterType(this)">
                <option value="">Select Field</option>
                ${Object.keys(availableFilters).map(field => 
                    `<option value="${field}">${availableFilters[field].label}</option>`
                ).join('')}
            </select>
            <select name="filters[${filterCount}][operator]" class="w-32 rounded-lg border-gray-300 text-sm">
                <option value="=">=</option>
                <option value="!=">≠</option>
                <option value=">">></option>
                <option value="<"><</option>
                <option value="between">Between</option>
                <option value="in">In</option>
                <option value="like">Contains</option>
            </select>
            <input type="text" name="filters[${filterCount}][value]" class="flex-1 rounded-lg border-gray-300 text-sm" placeholder="Value">
            <button type="button" onclick="removeFilter('${filterId}')" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', filterHtml);
}

function removeFilter(id) {
    document.getElementById(id)?.remove();
}

// Add sort
function addSort() {
    const container = document.getElementById('sorting-container');
    const sortId = 'sort-' + sortCount++;
    
    const sortHtml = `
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg" id="${sortId}">
            <select name="sorting[${sortCount}][field]" class="flex-1 rounded-lg border-gray-300 text-sm">
                <option value="">Select Field</option>
                ${Object.keys(availableFields).map(field => 
                    `<option value="${field}">${availableFields[field].label}</option>`
                ).join('')}
            </select>
            <select name="sorting[${sortCount}][direction]" class="w-32 rounded-lg border-gray-300 text-sm">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
            <button type="button" onclick="removeSort('${sortId}')" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', sortHtml);
}

function removeSort(id) {
    document.getElementById(id)?.remove();
}

// Preview report
async function previewReport() {
    const form = document.getElementById('report-builder-form');
    const formData = new FormData(form);
    
    // Get selected fields
    const selectedFields = Array.from(document.querySelectorAll('.field-checkbox:checked')).map(cb => cb.value);
    if (selectedFields.length === 0) {
        alert('Please select at least one field');
        return;
    }
    
    formData.append('selected_fields', JSON.stringify(selectedFields));
    
    // Get filters
    const filters = [];
    document.querySelectorAll('[name^="filters["]').forEach(input => {
        const match = input.name.match(/filters\[(\d+)\]\[(\w+)\]/);
        if (match) {
            const index = match[1];
            const key = match[2];
            if (!filters[index]) filters[index] = {};
            filters[index][key] = input.value;
        }
    });
    formData.append('filters', JSON.stringify(filters.filter(f => f.field && f.value)));
    
    // Get sorting
    const sorting = [];
    document.querySelectorAll('[name^="sorting["]').forEach(input => {
        const match = input.name.match(/sorting\[(\d+)\]\[(\w+)\]/);
        if (match) {
            const index = match[1];
            const key = match[2];
            if (!sorting[index]) sorting[index] = {};
            sorting[index][key] = input.value;
        }
    });
    formData.append('sorting', JSON.stringify(sorting.filter(s => s.field)));
    formData.append('limit', '50');
    
    try {
        const response = await fetch('{{ route("report-builder.preview") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showPreview(data);
        } else {
            alert('Error generating preview: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error generating preview');
    }
}

function showPreview(data) {
    const modal = document.getElementById('preview-modal');
    const content = document.getElementById('preview-content');
    
    let html = `<div class="mb-4 text-sm text-gray-600">Total Records: ${data.total}</div>`;
    
    // Show chart if enabled
    const chartType = document.getElementById('chart-type')?.value;
    const chartFieldX = document.querySelector('[name="chart_field_x"]')?.value;
    const chartFieldY = document.querySelector('[name="chart_field_y"]')?.value;
    
    if (chartType && chartFieldX && chartFieldY && data.data && data.data.length > 0) {
        html += '<div class="mb-6"><canvas id="report-chart" width="400" height="200"></canvas></div>';
    }
    
    html += '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">';
    
    // Header
    html += '<thead class="bg-gray-50"><tr>';
    Object.values(data.columns).forEach(col => {
        html += `<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">${col}</th>`;
    });
    html += '</tr></thead>';
    
    // Body
    html += '<tbody class="bg-white divide-y divide-gray-200">';
    data.data.forEach(row => {
        // Handle grouped rows
        const isGroupHeader = row._is_group_header;
        const isSubtotal = row._is_subtotal;
        const isGrandTotal = row._is_grand_total;
        
        let rowClass = '';
        if (isGroupHeader) {
            rowClass = 'bg-indigo-50 font-semibold';
        } else if (isSubtotal) {
            rowClass = 'bg-gray-100 font-medium';
        } else if (isGrandTotal) {
            rowClass = 'bg-indigo-100 font-bold';
        }
        
        html += `<tr class="${rowClass}">`;
        Object.keys(data.columns).forEach(field => {
            let cellValue = row[field] ?? '';
            if (isGroupHeader && row._group_label) {
                cellValue = row._group_label;
            } else if (isSubtotal && row._subtotal_label) {
                cellValue = field === Object.keys(data.columns)[0] ? row._subtotal_label : cellValue;
            } else if (isGrandTotal && row._grand_total_label) {
                cellValue = field === Object.keys(data.columns)[0] ? row._grand_total_label : cellValue;
            }
            html += `<td class="px-4 py-3 text-sm text-gray-900">${cellValue}</td>`;
        });
        html += '</tr>';
    });
    html += '</tbody></table></div>';
    
    content.innerHTML = html;
    modal.classList.remove('hidden');
    
    // Render chart if enabled
    if (chartType && chartFieldX && chartFieldY && data.data && data.data.length > 0 && typeof Chart !== 'undefined') {
        setTimeout(() => renderChart(data, chartType, chartFieldX, chartFieldY), 100);
    }
}

function renderChart(data, chartType, fieldX, fieldY) {
    const ctx = document.getElementById('report-chart');
    if (!ctx) return;
    
    // Prepare chart data
    const labels = [];
    const values = [];
    
    data.data.forEach(row => {
        if (!row._is_group_header && !row._is_subtotal && !row._is_grand_total) {
            labels.push(row[fieldX] || '');
            values.push(parseFloat(row[fieldY]) || 0);
        }
    });
    
    const chartConfig = {
        type: chartType,
        data: {
            labels: labels,
            datasets: [{
                label: data.columns[fieldY] || fieldY,
                data: values,
                backgroundColor: chartType === 'pie' || chartType === 'doughnut' 
                    ? ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899']
                    : '#3B82F6',
                borderColor: '#1E40AF',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: chartType === 'pie' || chartType === 'doughnut',
                    position: 'right'
                }
            }
        }
    };
    
    new Chart(ctx, chartConfig);
}

function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
}

// Export report
function exportReport(format) {
    const form = document.getElementById('report-builder-form');
    const formData = new FormData(form);
    
    const selectedFields = Array.from(document.querySelectorAll('.field-checkbox:checked')).map(cb => cb.value);
    if (selectedFields.length === 0) {
        alert('Please select at least one field');
        return;
    }
    
    formData.append('selected_fields', JSON.stringify(selectedFields));
    formData.append('format', format);
    
    // Create a form and submit
    const exportForm = document.createElement('form');
    exportForm.method = 'POST';
    exportForm.action = '{{ route("report-builder.export") }}';
    exportForm.style.display = 'none';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
    exportForm.appendChild(csrfInput);
    
    // Add form data
    for (const [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        exportForm.appendChild(input);
    }
    
    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}

// Load template
async function loadTemplate(templateId) {
    try {
        const response = await fetch(`/report-builder/${templateId}/load`);
        const data = await response.json();
        
        if (data.success) {
            const template = data.template;
            
            // Set basic info
            document.querySelector('[name="name"]').value = template.name;
            document.querySelector('[name="description"]').value = template.description || '';
            document.querySelector('[name="report_type"]').value = template.report_type;
            document.querySelector('[name="is_public"]').checked = template.is_public;
            document.querySelector('[name="is_default"]').checked = template.is_default;
            
            // Set selected fields
            document.querySelectorAll('.field-checkbox').forEach(cb => {
                cb.checked = template.selected_fields.includes(cb.value);
            });
            
            // Load grouping if exists
            if (template.grouping && template.grouping.fields) {
                document.getElementById('enable-grouping').checked = true;
                toggleGrouping();
                template.grouping.fields.forEach(field => addGroupField(field));
            }
            
            alert('Template loaded!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error loading template');
    }
}

// Grouping functions
let groupFieldCount = 0;
let aggregateFieldCount = 0;

function toggleGrouping() {
    const container = document.getElementById('grouping-container');
    const enabled = document.getElementById('enable-grouping').checked;
    container.classList.toggle('hidden', !enabled);
}

function addGroupField(field = '') {
    const container = document.getElementById('group-fields-container');
    const fieldId = 'group-field-' + groupFieldCount++;
    
    const html = `
        <div class="flex items-center gap-2" id="${fieldId}">
            <select name="grouping[fields][]" class="flex-1 rounded-lg border-gray-300 text-sm">
                <option value="">Select Field</option>
                ${Object.keys(availableFields).map(f => 
                    `<option value="${f}" ${f === field ? 'selected' : ''}>${availableFields[f].label}</option>`
                ).join('')}
            </select>
            <button type="button" onclick="removeGroupField('${fieldId}')" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeGroupField(id) {
    document.getElementById(id)?.remove();
}

function addAggregateField() {
    const container = document.getElementById('aggregate-fields-container');
    const fieldId = 'aggregate-field-' + aggregateFieldCount++;
    
    const numericFields = Object.keys(availableFields).filter(f => 
        availableFields[f].type === 'number'
    );
    
    const html = `
        <div class="flex items-center gap-2" id="${fieldId}">
            <select name="grouping[aggregates][field_${aggregateFieldCount}]" class="flex-1 rounded-lg border-gray-300 text-sm">
                <option value="">Select Field</option>
                ${numericFields.map(f => 
                    `<option value="${f}">${availableFields[f].label}</option>`
                ).join('')}
            </select>
            <select name="grouping[aggregates][function_${aggregateFieldCount}]" class="w-32 rounded-lg border-gray-300 text-sm">
                <option value="sum">Sum</option>
                <option value="avg">Average</option>
                <option value="count">Count</option>
                <option value="min">Min</option>
                <option value="max">Max</option>
            </select>
            <button type="button" onclick="removeAggregateField('${fieldId}')" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeAggregateField(id) {
    document.getElementById(id)?.remove();
}

// Charts functions
function toggleCharts() {
    const container = document.getElementById('charts-container');
    const enabled = document.getElementById('enable-charts').checked;
    container.classList.toggle('hidden', !enabled);
    
    if (enabled) {
        updateChartFields();
    }
}

function updateChartFields() {
    const xSelect = document.getElementById('chart-field-x');
    const ySelect = document.getElementById('chart-field-y');
    
    if (!xSelect || !ySelect) return;
    
    xSelect.innerHTML = '<option value="">Select Field</option>';
    ySelect.innerHTML = '<option value="">Select Field</option>';
    
    Object.keys(availableFields).forEach(field => {
        const option = `<option value="${field}">${availableFields[field].label}</option>`;
        xSelect.innerHTML += option;
        if (availableFields[field].type === 'number') {
            ySelect.innerHTML += option;
        }
    });
}

// Update chart fields when report type changes
document.getElementById('report-type')?.addEventListener('change', function() {
    setTimeout(updateChartFields, 100);
});
</script>

<!-- Chart.js for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
