<?php

namespace App\Http\Controllers;

use App\Models\ReportTemplate;
use App\Models\ScheduledReport;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\TestOrder;
use App\Models\Bill;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportBuilderController extends Controller
{
    /**
     * Display list of report templates.
     */
    public function index(Request $request): View
    {
        $labId = currentLabId();
        
        $query = ReportTemplate::forCurrentLab()
            ->with('creator')
            ->orderBy('usage_count', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by report type
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        // Filter by public/private
        if ($request->has('visibility')) {
            if ($request->visibility === 'public') {
                $query->public();
            } elseif ($request->visibility === 'private') {
                $query->where('is_public', false)
                      ->where('created_by', Auth::id());
            }
        }

        $templates = $query->paginate(20);
        
        // Get available report types
        $reportTypes = $this->getAvailableReportTypes();

        return view('report-builder.index', compact('templates', 'reportTypes'));
    }

    /**
     * Show the report builder interface.
     */
    public function create(Request $request): View
    {
        $reportType = $request->get('type', 'patients');
        $availableFields = $this->getAvailableFields($reportType);
        $availableFilters = $this->getAvailableFilters($reportType);
        
        // Load existing templates for this type
        $templates = ReportTemplate::forCurrentLab()
            ->ofType($reportType)
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get();

        return view('report-builder.create', compact(
            'reportType',
            'availableFields',
            'availableFilters',
            'templates'
        ));
    }

    /**
     * Store a new report template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'report_type' => 'required|string|in:patients,appointments,orders,bills,doctors',
            'selected_fields' => 'required|array|min:1',
            'selected_fields.*' => 'required|string',
            'filters' => 'nullable|array',
            'sorting' => 'nullable|array',
            'grouping' => 'nullable|array',
            'formatting' => 'nullable|array',
            'is_public' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // If setting as default, unset other defaults for this type
        if ($validated['is_default'] ?? false) {
            ReportTemplate::forCurrentLab()
                ->ofType($validated['report_type'])
                ->update(['is_default' => false]);
        }

        $template = ReportTemplate::create([
            'lab_id' => currentLabId(),
            'created_by' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'report_type' => $validated['report_type'],
            'selected_fields' => $validated['selected_fields'],
            'filters' => $validated['filters'] ?? [],
            'sorting' => $validated['sorting'] ?? [],
            'grouping' => $validated['grouping'] ?? null,
            'formatting' => $validated['formatting'] ?? [],
            'is_public' => $validated['is_public'] ?? false,
            'is_default' => $validated['is_default'] ?? false,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'template' => $template,
                'message' => 'Report template saved successfully.',
            ]);
        }

        return redirect()
            ->route('report-builder.index')
            ->with('success', 'Report template created successfully.');
    }

    /**
     * Generate and preview report.
     */
    public function preview(Request $request): JsonResponse|View
    {
        $validated = $request->validate([
            'report_type' => 'required|string|in:patients,appointments,orders,bills,doctors',
            'selected_fields' => 'required|array|min:1',
            'filters' => 'nullable|array',
            'sorting' => 'nullable|array',
            'grouping' => 'nullable|array',
            'limit' => 'nullable|integer|min:1|max:1000',
        ]);

        $data = $this->generateReportData(
            $validated['report_type'],
            $validated['selected_fields'],
            $validated['filters'] ?? [],
            $validated['sorting'] ?? [],
            $validated['grouping'] ?? null,
            $validated['limit'] ?? 100
        );

        // Apply formatting if provided
        if (isset($validated['formatting']) && !empty($validated['formatting'])) {
            $data = $this->applyFormatting($data, $validated['formatting'], $validated['report_type']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data['rows'],
                'total' => $data['total'],
                'columns' => $data['columns'],
            ]);
        }

        return view('report-builder.preview', [
            'data' => $data['rows'],
            'columns' => $data['columns'],
            'total' => $data['total'],
            'reportType' => $validated['report_type'],
        ]);
    }

    /**
     * Export report.
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|string|in:patients,appointments,orders,bills,doctors',
            'selected_fields' => 'required|array|min:1',
            'filters' => 'nullable|array',
            'sorting' => 'nullable|array',
            'grouping' => 'nullable|array',
            'formatting' => 'nullable|array',
            'format' => 'required|string|in:csv,excel,pdf',
        ]);

        $data = $this->generateReportData(
            $validated['report_type'],
            $validated['selected_fields'],
            $validated['filters'] ?? [],
            $validated['sorting'] ?? [],
            $validated['grouping'] ?? null,
            null // No limit for exports
        );

        // Apply formatting if provided
        if (isset($validated['formatting']) && !empty($validated['formatting'])) {
            $data = $this->applyFormatting($data, $validated['formatting'], $validated['report_type']);
        }

        $filename = $validated['report_type'] . '_report_' . date('Y-m-d_His');

        switch ($validated['format']) {
            case 'csv':
                return $this->exportCsv($data, $filename);
            case 'excel':
                return $this->exportExcel($data, $filename);
            case 'pdf':
                return $this->exportPdf($data, $filename, $validated['report_type']);
        }
    }

    /**
     * Edit a report template.
     */
    public function edit(ReportTemplate $template): View
    {
        if ($template->lab_id !== currentLabId()) {
            abort(404);
        }

        $availableFields = $this->getAvailableFields($template->report_type);
        $availableFilters = $this->getAvailableFilters($template->report_type);
        $reportTypes = $this->getAvailableReportTypes();

        return view('report-builder.edit', compact('template', 'availableFields', 'availableFilters', 'reportTypes'));
    }

    /**
     * Update a report template.
     */
    public function update(Request $request, ReportTemplate $template)
    {
        if ($template->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'selected_fields' => 'required|array|min:1',
            'filters' => 'nullable|array',
            'sorting' => 'nullable|array',
            'grouping' => 'nullable|array',
            'formatting' => 'nullable|array',
            'is_public' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // If setting as default, unset other defaults for this type
        if ($validated['is_default'] ?? false) {
            ReportTemplate::forCurrentLab()
                ->ofType($template->report_type)
                ->where('id', '!=', $template->id)
                ->update(['is_default' => false]);
        }

        $template->update($validated);

        return redirect()
            ->route('report-builder.index')
            ->with('success', 'Report template updated successfully.');
    }

    /**
     * Delete a report template.
     */
    public function destroy(ReportTemplate $template)
    {
        if ($template->lab_id !== currentLabId()) {
            abort(404);
        }

        $template->delete();

        return redirect()
            ->route('report-builder.index')
            ->with('success', 'Report template deleted successfully.');
    }

    /**
     * Load a template and return its configuration.
     */
    public function load(ReportTemplate $template): JsonResponse
    {
        if ($template->lab_id !== currentLabId()) {
            abort(404);
        }

        $template->incrementUsage();

        return response()->json([
            'success' => true,
            'template' => $template,
        ]);
    }

    /**
     * Get available report types.
     */
    protected function getAvailableReportTypes(): array
    {
        return [
            'patients' => 'Patients',
            'appointments' => 'Appointments',
            'orders' => 'Test Orders',
            'bills' => 'Bills',
            'doctors' => 'Doctors',
        ];
    }

    /**
     * Get available fields for a report type.
     */
    protected function getAvailableFields(string $reportType): array
    {
        $fields = [
            'patients' => [
                'id' => ['label' => 'ID', 'type' => 'number'],
                'name' => ['label' => 'Name', 'type' => 'string'],
                'phone' => ['label' => 'Phone', 'type' => 'string'],
                'email' => ['label' => 'Email', 'type' => 'string'],
                'age' => ['label' => 'Age', 'type' => 'number'],
                'gender' => ['label' => 'Gender', 'type' => 'string'],
                'address' => ['label' => 'Address', 'type' => 'string'],
                'created_at' => ['label' => 'Registration Date', 'type' => 'date'],
            ],
            'appointments' => [
                'id' => ['label' => 'ID', 'type' => 'number'],
                'patient_name' => ['label' => 'Patient Name', 'type' => 'string'],
                'appointment_date' => ['label' => 'Date', 'type' => 'date'],
                'appointment_time' => ['label' => 'Time', 'type' => 'time'],
                'status' => ['label' => 'Status', 'type' => 'string'],
                'package_name' => ['label' => 'Package', 'type' => 'string'],
                'booking_source' => ['label' => 'Source', 'type' => 'string'],
                'created_at' => ['label' => 'Created At', 'type' => 'date'],
            ],
            'orders' => [
                'id' => ['label' => 'ID', 'type' => 'number'],
                'patient_name' => ['label' => 'Patient Name', 'type' => 'string'],
                'test_name' => ['label' => 'Test Name', 'type' => 'string'],
                'status' => ['label' => 'Status', 'type' => 'string'],
                'price' => ['label' => 'Price', 'type' => 'number'],
                'final_price' => ['label' => 'Final Price', 'type' => 'number'],
                'created_at' => ['label' => 'Order Date', 'type' => 'date'],
            ],
            'bills' => [
                'id' => ['label' => 'ID', 'type' => 'number'],
                'patient_name' => ['label' => 'Patient Name', 'type' => 'string'],
                'bill_number' => ['label' => 'Bill Number', 'type' => 'string'],
                'total_amount' => ['label' => 'Total Amount', 'type' => 'number'],
                'paid_amount' => ['label' => 'Paid Amount', 'type' => 'number'],
                'balance' => ['label' => 'Balance', 'type' => 'number'],
                'status' => ['label' => 'Status', 'type' => 'string'],
                'bill_date' => ['label' => 'Bill Date', 'type' => 'date'],
            ],
            'doctors' => [
                'id' => ['label' => 'ID', 'type' => 'number'],
                'name' => ['label' => 'Name', 'type' => 'string'],
                'specialization' => ['label' => 'Specialization', 'type' => 'string'],
                'phone' => ['label' => 'Phone', 'type' => 'string'],
                'email' => ['label' => 'Email', 'type' => 'string'],
                'total_referrals' => ['label' => 'Total Referrals', 'type' => 'number'],
                'pending_commission' => ['label' => 'Pending Commission', 'type' => 'number'],
                'is_active' => ['label' => 'Active', 'type' => 'boolean'],
            ],
        ];

        return $fields[$reportType] ?? [];
    }

    /**
     * Get available filters for a report type.
     */
    protected function getAvailableFilters(string $reportType): array
    {
        $filters = [
            'patients' => [
                'created_at' => ['label' => 'Registration Date', 'type' => 'date_range'],
                'gender' => ['label' => 'Gender', 'type' => 'select', 'options' => ['male', 'female', 'other']],
            ],
            'appointments' => [
                'appointment_date' => ['label' => 'Appointment Date', 'type' => 'date_range'],
                'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show']],
                'booking_source' => ['label' => 'Source', 'type' => 'select', 'options' => ['admin', 'public', 'doctor']],
            ],
            'orders' => [
                'created_at' => ['label' => 'Order Date', 'type' => 'date_range'],
                'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending', 'in_progress', 'completed', 'cancelled']],
            ],
            'bills' => [
                'bill_date' => ['label' => 'Bill Date', 'type' => 'date_range'],
                'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending', 'partial', 'paid']],
            ],
            'doctors' => [
                'is_active' => ['label' => 'Active Status', 'type' => 'select', 'options' => [true, false]],
            ],
        ];

        return $filters[$reportType] ?? [];
    }

    /**
     * Generate report data based on configuration.
     */
    protected function generateReportData(
        string $reportType,
        array $selectedFields,
        array $filters,
        array $sorting,
        ?array $grouping,
        ?int $limit
    ): array {
        $labId = currentLabId();
        $query = null;

        switch ($reportType) {
            case 'patients':
                $query = $this->buildPatientsQuery($labId, $selectedFields, $filters, $sorting);
                break;
            case 'appointments':
                $query = $this->buildAppointmentsQuery($labId, $selectedFields, $filters, $sorting);
                break;
            case 'orders':
                $query = $this->buildOrdersQuery($labId, $selectedFields, $filters, $sorting);
                break;
            case 'bills':
                $query = $this->buildBillsQuery($labId, $selectedFields, $filters, $sorting);
                break;
            case 'doctors':
                $query = $this->buildDoctorsQuery($labId, $selectedFields, $filters, $sorting);
                break;
        }

        if (!$query) {
            return ['rows' => [], 'columns' => [], 'total' => 0, 'grouped' => false];
        }

        $total = $query->count();

        // Apply grouping if specified
        if ($grouping && !empty($grouping['fields'])) {
            return $this->generateGroupedReportData($query, $selectedFields, $grouping, $reportType, $limit);
        }

        if ($limit) {
            $query->limit($limit);
        }

        $rows = $query->get()->map(function ($row) use ($selectedFields) {
            $data = [];
            foreach ($selectedFields as $field) {
                $data[$field] = $this->getFieldValue($row, $field);
            }
            return $data;
        })->toArray();

        $columns = [];
        foreach ($selectedFields as $field) {
            $availableFields = $this->getAvailableFields($reportType);
            $columns[$field] = $availableFields[$field]['label'] ?? $field;
        }

        return [
            'rows' => $rows,
            'columns' => $columns,
            'total' => $total,
            'grouped' => false,
        ];
    }

    /**
     * Generate grouped report data with subtotals.
     */
    protected function generateGroupedReportData($query, array $selectedFields, array $grouping, string $reportType, ?int $limit): array
    {
        $groupFields = $grouping['fields'] ?? [];
        
        // Parse aggregate fields from grouping config
        $aggregateFields = [];
        if (isset($grouping['aggregates']) && is_array($grouping['aggregates'])) {
            foreach ($grouping['aggregates'] as $key => $value) {
                if (str_starts_with($key, 'field_')) {
                    $index = str_replace('field_', '', $key);
                    $functionKey = 'function_' . $index;
                    if (isset($grouping['aggregates'][$functionKey]) && !empty($value)) {
                        $aggregateFields[$value] = $grouping['aggregates'][$functionKey];
                    }
                } elseif (is_string($key) && !str_starts_with($key, 'function_')) {
                    // Direct format: ['price' => 'sum']
                    $aggregateFields[$key] = $value;
                }
            }
        }
        
        if (empty($groupFields)) {
            return $this->generateReportData($reportType, $selectedFields, [], [], null, $limit);
        }

        // Get all data
        $allData = $query->get();
        
        // Group data
        $grouped = [];
        $subtotals = [];
        
        foreach ($allData as $row) {
            $groupKey = [];
            foreach ($groupFields as $field) {
                $groupKey[] = $this->getFieldValue($row, $field);
            }
            $key = implode('|', $groupKey);
            
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'group_values' => [],
                    'rows' => [],
                    'subtotal' => [],
                ];
                
                foreach ($groupFields as $field) {
                    $grouped[$key]['group_values'][$field] = $this->getFieldValue($row, $field);
                }
            }
            
            $rowData = [];
            foreach ($selectedFields as $field) {
                $rowData[$field] = $this->getFieldValue($row, $field);
            }
            $grouped[$key]['rows'][] = $rowData;
        }
        
        // Calculate subtotals for each group
        foreach ($grouped as $key => $group) {
            $subtotal = [];
            foreach ($aggregateFields as $field => $function) {
                $values = array_column($group['rows'], $field);
                $values = array_filter($values, function($v) { return $v !== null && $v !== ''; });
                
                switch ($function) {
                    case 'sum':
                        $subtotal[$field] = array_sum($values);
                        break;
                    case 'avg':
                        $subtotal[$field] = count($values) > 0 ? array_sum($values) / count($values) : 0;
                        break;
                    case 'count':
                        $subtotal[$field] = count($group['rows']);
                        break;
                    case 'min':
                        $subtotal[$field] = count($values) > 0 ? min($values) : 0;
                        break;
                    case 'max':
                        $subtotal[$field] = count($values) > 0 ? max($values) : 0;
                        break;
                    default:
                        $subtotal[$field] = null;
                }
            }
            $grouped[$key]['subtotal'] = $subtotal;
        }
        
        // Build final rows with subtotals
        $finalRows = [];
        $grandTotal = [];
        
        foreach ($grouped as $key => $group) {
            // Add group header
            $groupHeader = [];
            foreach ($selectedFields as $field) {
                if (in_array($field, $groupFields)) {
                    $groupHeader[$field] = $group['group_values'][$field] ?? '';
                } else {
                    $groupHeader[$field] = '';
                }
            }
            $groupHeader['_is_group_header'] = true;
            $groupHeader['_group_label'] = implode(' - ', array_filter($group['group_values']));
            $finalRows[] = $groupHeader;
            
            // Add group rows
            foreach ($group['rows'] as $row) {
                $row['_is_group_row'] = true;
                $finalRows[] = $row;
            }
            
            // Add subtotal row
            $subtotalRow = [];
            foreach ($selectedFields as $field) {
                if (in_array($field, $groupFields)) {
                    $subtotalRow[$field] = '';
                } elseif (isset($group['subtotal'][$field])) {
                    $subtotalRow[$field] = $group['subtotal'][$field];
                } else {
                    $subtotalRow[$field] = '';
                }
            }
            $subtotalRow['_is_subtotal'] = true;
            $subtotalRow['_subtotal_label'] = 'Subtotal';
            $finalRows[] = $subtotalRow;
            
            // Accumulate grand total
            $aggregates = [];
            if (isset($grouping['aggregates']) && is_array($grouping['aggregates'])) {
                foreach ($grouping['aggregates'] as $aggKey => $aggValue) {
                    if (str_starts_with($aggKey, 'field_')) {
                        $index = str_replace('field_', '', $aggKey);
                        $functionKey = 'function_' . $index;
                        if (isset($grouping['aggregates'][$functionKey])) {
                            $aggregates[$aggValue] = $grouping['aggregates'][$functionKey];
                        }
                    }
                }
            }
            
            foreach ($aggregates as $field => $function) {
                if (!isset($grandTotal[$field])) {
                    $grandTotal[$field] = 0;
                }
                if (isset($group['subtotal'][$field])) {
                    if ($function === 'sum' || $function === 'count') {
                        $grandTotal[$field] += $group['subtotal'][$field];
                    }
                }
            }
        }
        
        // Add grand total row
        if (!empty($grandTotal)) {
            $grandTotalRow = [];
            foreach ($selectedFields as $field) {
                if (isset($grandTotal[$field])) {
                    $grandTotalRow[$field] = $grandTotal[$field];
                } else {
                    $grandTotalRow[$field] = '';
                }
            }
            $grandTotalRow['_is_grand_total'] = true;
            $grandTotalRow['_grand_total_label'] = 'Grand Total';
            $finalRows[] = $grandTotalRow;
        }
        
        // Apply limit if specified
        if ($limit && count($finalRows) > $limit) {
            $finalRows = array_slice($finalRows, 0, $limit);
        }
        
        $columns = [];
        foreach ($selectedFields as $field) {
            $availableFields = $this->getAvailableFields($reportType);
            $columns[$field] = $availableFields[$field]['label'] ?? $field;
        }
        
        $aggregates = [];
        if (isset($grouping['aggregates']) && is_array($grouping['aggregates'])) {
            foreach ($grouping['aggregates'] as $aggKey => $aggValue) {
                if (str_starts_with($aggKey, 'field_')) {
                    $index = str_replace('field_', '', $aggKey);
                    $functionKey = 'function_' . $index;
                    if (isset($grouping['aggregates'][$functionKey])) {
                        $aggregates[$aggValue] = $grouping['aggregates'][$functionKey];
                    }
                }
            }
        }
        
        return [
            'rows' => $finalRows,
            'columns' => $columns,
            'total' => count($allData),
            'grouped' => true,
            'group_fields' => $groupFields,
            'aggregate_fields' => $aggregates,
        ];
    }

    /**
     * Build patients query.
     */
    protected function buildPatientsQuery($labId, $selectedFields, $filters, $sorting)
    {
        $query = Patient::where('lab_id', $labId);

        // Apply filters
        foreach ($filters as $filter) {
            $this->applyFilter($query, $filter);
        }

        // Apply sorting
        foreach ($sorting as $sort) {
            $query->orderBy($sort['field'], $sort['direction'] ?? 'asc');
        }

        return $query;
    }

    /**
     * Build appointments query.
     */
    protected function buildAppointmentsQuery($labId, $selectedFields, $filters, $sorting)
    {
        $query = Appointment::where('appointments.lab_id', $labId)
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->select('appointments.*', 'patients.name as patient_name');

        if (in_array('package_name', $selectedFields)) {
            $query->leftJoin('test_packages', 'appointments.package_id', '=', 'test_packages.id')
                  ->addSelect('test_packages.name as package_name');
        }

        // Apply filters
        foreach ($filters as $filter) {
            $this->applyFilter($query, $filter);
        }

        // Apply sorting
        foreach ($sorting as $sort) {
            $query->orderBy($sort['field'], $sort['direction'] ?? 'asc');
        }

        return $query;
    }

    /**
     * Build orders query.
     */
    protected function buildOrdersQuery($labId, $selectedFields, $filters, $sorting)
    {
        $query = TestOrder::where('test_orders.lab_id', $labId)
            ->join('patients', 'test_orders.patient_id', '=', 'patients.id')
            ->join('tests', 'test_orders.test_id', '=', 'tests.id')
            ->select('test_orders.*', 'patients.name as patient_name', 'tests.name as test_name');

        // Apply filters
        foreach ($filters as $filter) {
            $this->applyFilter($query, $filter);
        }

        // Apply sorting
        foreach ($sorting as $sort) {
            $query->orderBy($sort['field'], $sort['direction'] ?? 'asc');
        }

        return $query;
    }

    /**
     * Build bills query.
     */
    protected function buildBillsQuery($labId, $selectedFields, $filters, $sorting)
    {
        $query = Bill::where('bills.lab_id', $labId)
            ->join('patients', 'bills.patient_id', '=', 'patients.id')
            ->select('bills.*', 'patients.name as patient_name');

        // Apply filters
        foreach ($filters as $filter) {
            $this->applyFilter($query, $filter);
        }

        // Apply sorting
        foreach ($sorting as $sort) {
            $query->orderBy($sort['field'], $sort['direction'] ?? 'asc');
        }

        return $query;
    }

    /**
     * Build doctors query.
     */
    protected function buildDoctorsQuery($labId, $selectedFields, $filters, $sorting)
    {
        $query = Doctor::where('doctors.lab_id', $labId)
            ->withCount('testOrders as total_referrals');

        // Apply filters
        foreach ($filters as $filter) {
            $this->applyFilter($query, $filter);
        }

        // Apply sorting
        foreach ($sorting as $sort) {
            $query->orderBy($sort['field'], $sort['direction'] ?? 'asc');
        }

        return $query;
    }

    /**
     * Apply a filter to query.
     */
    protected function applyFilter($query, array $filter)
    {
        $field = $filter['field'] ?? null;
        $operator = $filter['operator'] ?? '=';
        $value = $filter['value'] ?? null;

        if (!$field || $value === null || $value === '') {
            return;
        }

        switch ($operator) {
            case 'between':
                if (isset($value['from']) && isset($value['to'])) {
                    $query->whereBetween($field, [$value['from'], $value['to']]);
                }
                break;
            case 'in':
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                }
                break;
            case 'like':
                $query->where($field, 'like', "%{$value}%");
                break;
            default:
                $query->where($field, $operator, $value);
        }
    }

    /**
     * Get field value from row.
     */
    protected function getFieldValue($row, string $field)
    {
        if (str_contains($field, '.')) {
            $parts = explode('.', $field);
            $value = $row;
            foreach ($parts as $part) {
                $value = $value->$part ?? null;
            }
            return $value;
        }

        return $row->$field ?? null;
    }

    /**
     * Export to CSV.
     */
    protected function exportCsv(array $data, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($file, array_values($data['columns']));
            
            // Write data
            foreach ($data['rows'] as $row) {
                $values = [];
                foreach (array_keys($data['columns']) as $field) {
                    $values[] = $row[$field] ?? '';
                }
                fputcsv($file, $values);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel.
     */
    protected function exportExcel(array $data, string $filename)
    {
        // For now, return CSV as Excel (can be enhanced with PhpSpreadsheet later)
        return $this->exportCsv($data, $filename);
    }

    /**
     * Apply formatting to report data.
     */
    protected function applyFormatting(array $data, array $formatting, string $reportType): array
    {
        $dateFormat = $formatting['date_format'] ?? 'Y-m-d';
        $numberFormat = $formatting['number_format'] ?? '2';
        $currencySymbol = $formatting['currency_symbol'] ?? '₹';
        
        $availableFields = $this->getAvailableFields($reportType);
        
        foreach ($data['rows'] as &$row) {
            foreach ($row as $field => &$value) {
                if ($value === null || $value === '' || isset($row['_is_group_header']) || isset($row['_is_subtotal']) || isset($row['_is_grand_total'])) {
                    continue;
                }
                
                $fieldConfig = $availableFields[$field] ?? null;
                
                // Format dates
                if ($fieldConfig && $fieldConfig['type'] === 'date' && $value) {
                    try {
                        $date = \Carbon\Carbon::parse($value);
                        $value = $date->format($dateFormat);
                    } catch (\Exception $e) {
                        // Keep original value if parsing fails
                    }
                }
                
                // Format numbers
                if ($fieldConfig && $fieldConfig['type'] === 'number' && is_numeric($value)) {
                    $value = number_format((float)$value, (int)$numberFormat);
                    
                    // Add currency symbol for price/amount fields
                    if (str_contains($field, 'price') || str_contains($field, 'amount') || str_contains($field, 'commission')) {
                        $value = $currencySymbol . ' ' . $value;
                    }
                }
            }
        }
        
        return $data;
    }

    /**
     * Export to PDF.
     */
    protected function exportPdf(array $data, string $filename, string $reportType)
    {
        $lab = \App\Models\Lab::find(currentLabId());
        $settings = \App\Models\WebsiteSetting::getForCurrentLab();
        
        return view('report-builder.pdf', [
            'data' => $data['rows'],
            'columns' => $data['columns'],
            'total' => $data['total'],
            'reportType' => $reportType,
            'filename' => $filename,
            'lab' => $lab,
            'settings' => $settings,
            'grouped' => $data['grouped'] ?? false,
        ]);
    }

    /**
     * Show scheduled reports management page.
     */
    public function scheduled(): View
    {
        $scheduledReports = ScheduledReport::forCurrentLab()
            ->with(['template', 'creator'])
            ->orderBy('next_run_at', 'asc')
            ->paginate(20);

        return view('report-builder.scheduled', compact('scheduledReports'));
    }

    /**
     * Show form to create scheduled report.
     */
    public function createScheduled(Request $request): View
    {
        $templateId = $request->get('template_id');
        $templates = ReportTemplate::forCurrentLab()
            ->orderBy('name')
            ->get();

        return view('report-builder.create-scheduled', compact('templates', 'templateId'));
    }

    /**
     * Store scheduled report.
     */
    public function storeScheduled(Request $request)
    {
        $validated = $request->validate([
            'report_template_id' => 'required|exists:report_templates,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'frequency' => 'required|in:daily,weekly,monthly,custom',
            'send_time' => 'required|date_format:H:i',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'required|email',
            'format' => 'required|in:pdf,excel,csv',
            'schedule_config' => 'nullable|array',
        ]);

        $template = ReportTemplate::findOrFail($validated['report_template_id']);
        if ($template->lab_id !== currentLabId()) {
            abort(404);
        }

        $scheduledReport = ScheduledReport::create([
            'lab_id' => currentLabId(),
            'report_template_id' => $validated['report_template_id'],
            'created_by' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'frequency' => $validated['frequency'],
            'schedule_config' => $validated['schedule_config'] ?? [],
            'send_time' => $validated['send_time'],
            'recipients' => $validated['recipients'],
            'format' => $validated['format'],
            'next_run_at' => (new ScheduledReport())->fill($validated)->calculateNextRun(),
        ]);

        return redirect()
            ->route('report-builder.scheduled')
            ->with('success', 'Scheduled report created successfully.');
    }

    /**
     * Toggle scheduled report active status.
     */
    public function toggleScheduled(ScheduledReport $scheduledReport): JsonResponse
    {
        if ($scheduledReport->lab_id !== currentLabId()) {
            abort(404);
        }

        $scheduledReport->update([
            'is_active' => !$scheduledReport->is_active,
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $scheduledReport->is_active,
        ]);
    }

    /**
     * Delete scheduled report.
     */
    public function destroyScheduled(ScheduledReport $scheduledReport)
    {
        if ($scheduledReport->lab_id !== currentLabId()) {
            abort(404);
        }

        $scheduledReport->delete();

        return redirect()
            ->route('report-builder.scheduled')
            ->with('success', 'Scheduled report deleted successfully.');
    }
}
