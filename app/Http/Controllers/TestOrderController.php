<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Report;
use App\Models\ReportTest;
use App\Models\Technician;
use App\Models\Test;
use App\Models\TestOrder;
use App\Models\TestPackage;
use App\Services\InventoryService;
use App\Services\TestOrderWorkflowService;
use App\Services\TestResultService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $labId = currentLabId();

        $query = TestOrder::where('lab_id', $labId)
            ->with(['patient', 'test', 'package', 'technician', 'collectedBy']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhereHas('test', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by sample status
        if ($request->filled('sample_status')) {
            $query->where('sample_status', $request->sample_status);
        }

        // Filter by report status
        if ($request->filled('report_status')) {
            $query->where('report_status', $request->report_status);
        }

        // Filter by order type (package vs test)
        if ($request->filled('order_type')) {
            if ($request->order_type === 'package') {
                $query->whereNotNull('package_id');
            } else {
                $query->whereNull('package_id');
            }
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Statistics - Use caching for better performance
        $cacheKey = "test_order_stats_{$labId}";
        $stats = cache()->remember($cacheKey, 300, function () use ($labId) {
            return [
                'total' => TestOrder::where('lab_id', $labId)->count(),
                'pending' => TestOrder::where('lab_id', $labId)->where('sample_status', 'pending')->count(),
                'processing' => TestOrder::where('lab_id', $labId)->where('status', 'processing')->count(),
                'completed' => TestOrder::where('lab_id', $labId)->where('status', 'completed')->count(),
                'reports_ready' => TestOrder::where('lab_id', $labId)->where('report_status', 'completed')->count(),
            ];
        });

        return view('orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $labId = currentLabId();
        $patients = Patient::where('lab_id', $labId)->get();
        $tests = Test::where('lab_id', $labId)->get();
        $packages = TestPackage::where('lab_id', $labId)->where('is_active', true)->with('tests')->get();
        // Only load technicians if lab is large level
        $technicians = isLargeLab() ? Technician::where('lab_id', $labId)->get() : collect();

        $appointment = null;
        if ($request->filled('appointment_id')) {
            $appointment = Appointment::where('lab_id', $labId)
                ->whereKey($request->integer('appointment_id'))
                ->with(['package', 'tests'])
                ->first();
            if ($appointment && $request->filled('patient_id')
                && (int) $request->input('patient_id') !== (int) $appointment->patient_id) {
                $appointment = null;
            }
        }

        if ($request->session()->hasOldInput()) {
            $defaultPatientId = old('patient_id');
            $defaultOrderType = old('order_type', 'test');
            $defaultTestId = old('test_id');
            $defaultPackageId = old('package_id');
        } else {
            $defaultPatientId = $request->input('patient_id');
            $defaultOrderType = 'test';
            $defaultTestId = null;
            $defaultPackageId = null;

            if ($appointment) {
                $defaultPatientId = $appointment->patient_id;
                if ($appointment->package_id) {
                    $defaultOrderType = 'package';
                    $defaultPackageId = $appointment->package_id;
                } elseif ($appointment->tests->isNotEmpty()) {
                    $defaultOrderType = 'test';
                    $defaultTestId = $appointment->tests->first()->id;
                }
            }
        }

        return view('orders.create', compact(
            'patients',
            'tests',
            'packages',
            'technicians',
            'appointment',
            'defaultPatientId',
            'defaultOrderType',
            'defaultTestId',
            'defaultPackageId'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation rules
        $rules = [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'order_type' => 'required|in:test,package',
            'status' => 'nullable|in:pending,sample_collected,processing,completed,report_uploaded',
            'technician_id' => 'nullable|exists:technicians,id',
        ];

        // Conditional validation based on order_type
        if ($request->order_type === 'test') {
            $rules['test_id'] = 'required|exists:tests,id';
            $rules['package_id'] = 'nullable'; // Ignore package_id for single test orders
        } else {
            $rules['package_id'] = 'required|exists:test_packages,id';
            $rules['test_id'] = 'nullable'; // Ignore test_id for package orders
        }

        $validated = $request->validate($rules);

        // Remove fields that shouldn't be processed based on order_type
        if ($validated['order_type'] === 'test') {
            unset($validated['package_id']);
        } else {
            unset($validated['test_id']);
        }

        $appointmentId = null;
        if (! empty($validated['appointment_id'])) {
            $linked = Appointment::where('id', $validated['appointment_id'])
                ->where('lab_id', currentLabId())
                ->where('patient_id', $validated['patient_id'])
                ->exists();
            if ($linked) {
                $appointmentId = (int) $validated['appointment_id'];
            }
        }
        unset($validated['appointment_id']);

        // Verify patient belongs to current lab
        $patient = Patient::where('id', $validated['patient_id'])
            ->where('lab_id', currentLabId())
            ->firstOrFail();

        DB::beginTransaction();
        try {
            if ($validated['order_type'] === 'package') {
                // Handle package order - create orders for all tests in package
                $package = TestPackage::where('id', $validated['package_id'])
                    ->where('lab_id', currentLabId())
                    ->with('tests')
                    ->firstOrFail();

                $orders = [];
                foreach ($package->tests as $test) {
                    $orders[] = [
                        'lab_id' => currentLabId(),
                        'patient_id' => $validated['patient_id'],
                        'appointment_id' => $appointmentId,
                        'test_id' => $test->id,
                        'package_id' => $package->id,
                        'status' => $validated['status'] ?? 'pending',
                        'sample_status' => 'pending',
                        'report_status' => 'pending',
                        'price' => $test->price,
                        'final_price' => $test->price,
                        'technician_id' => isset($validated['technician_id']) && isLargeLab() ? $validated['technician_id'] : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $createdOrders = [];
                foreach ($orders as $orderData) {
                    $order = TestOrder::create($orderData);
                    $createdOrders[] = $order;

                    // Auto-load parameters for each test order
                    try {
                        $testResultService = app(TestResultService::class);
                        $testResultService->loadParametersForOrder($order);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to load parameters for order '.$order->id.': '.$e->getMessage());
                        // Continue even if parameter loading fails
                    }
                }
                $message = "Package order created successfully. {$package->tests->count()} test orders created.";
            } else {
                // Handle single test order
                $test = Test::where('id', $validated['test_id'])
                    ->where('lab_id', currentLabId())
                    ->firstOrFail();

                $orderData = [
                    'lab_id' => currentLabId(),
                    'patient_id' => $validated['patient_id'],
                    'appointment_id' => $appointmentId,
                    'test_id' => $validated['test_id'],
                    'status' => $validated['status'] ?? 'pending',
                    'sample_status' => 'pending',
                    'report_status' => 'pending',
                    'price' => $test->price,
                    'final_price' => $test->price,
                ];

                // Verify technician belongs to current lab if provided (only for large labs)
                if (isset($validated['technician_id']) && isLargeLab()) {
                    $technician = Technician::where('id', $validated['technician_id'])
                        ->where('lab_id', currentLabId())
                        ->firstOrFail();
                    $orderData['technician_id'] = $validated['technician_id'];
                }

                $order = TestOrder::create($orderData);

                // Auto-load parameters for test order
                try {
                    $testResultService = app(TestResultService::class);
                    $testResultService->loadParametersForOrder($order);
                } catch (\Exception $e) {
                    \Log::warning('Failed to load parameters for order '.$order->id.': '.$e->getMessage());
                    // Continue even if parameter loading fails
                }

                $message = 'Test order created successfully.';
            }

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Test Order Creation Failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create order: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TestOrder $order)
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        $order->load(['patient', 'test', 'report', 'technician', 'collectedBy']);

        // Only load technicians if lab is large level
        $technicians = isLargeLab() ? Technician::where('lab_id', currentLabId())->get() : collect();

        // Get workflow information
        $workflowService = app(TestOrderWorkflowService::class);
        $workflowInfo = $workflowService->getNextAllowedStatus($order);
        $workflowSteps = $workflowService->getWorkflowSteps($order);

        return view('orders.show', compact('order', 'technicians', 'workflowInfo', 'workflowSteps'));
    }

    /**
     * Update the test order status.
     */
    public function updateStatus(Request $request, TestOrder $order): RedirectResponse
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,sample_collected,processing,completed,report_uploaded',
            'sample_status' => 'nullable|in:pending,collected,sent_to_lab',
            'report_status' => 'nullable|in:pending,completed',
            'technician_id' => 'nullable|exists:technicians,id',
        ]);

        // Use workflow service to validate and apply transitions
        $workflowService = app(TestOrderWorkflowService::class);
        $transition = $workflowService->applyTransition(
            $order,
            $validated['status'],
            $validated['sample_status'] ?? null,
            $validated['report_status'] ?? null
        );

        if (! $transition['valid']) {
            return redirect()->route('orders.show', $order)
                ->withErrors(['workflow' => implode(' ', $transition['errors'])]);
        }

        // Merge workflow updates with validated data
        $updates = array_merge($validated, $transition['updates']);

        // Handle sample collection timestamp
        if (isset($updates['sample_status']) && $updates['sample_status'] === 'collected') {
            if (! $order->sample_collected_at) {
                $updates['sample_collected_at'] = now();
                $updates['collected_by'] = Auth::id();
            }
        }

        // Verify technician belongs to current lab if provided (only for large labs)
        if (isset($updates['technician_id']) && isLargeLab()) {
            $technician = Technician::where('id', $updates['technician_id'])
                ->where('lab_id', currentLabId())
                ->firstOrFail();
        } elseif (isset($updates['technician_id']) && ! isLargeLab()) {
            // Remove technician_id if lab is not large level
            unset($updates['technician_id']);
        }

        $oldStatus = $order->status;
        $oldReportStatus = $order->report_status;

        // Check if status will change to completed or report_uploaded
        $willBeCompleted = ($updates['status'] === 'completed' && $oldStatus !== 'completed');
        $willBeReportUploaded = ($updates['status'] === 'report_uploaded' && $oldStatus !== 'report_uploaded');
        $reportStatusCompleted = (isset($updates['report_status']) && $updates['report_status'] === 'completed' && $oldReportStatus !== 'completed');

        $order->update($updates);

        $reportCreated = false;
        $statusUpgraded = false;

        // Auto-deduct inventory when test is completed
        if ($willBeCompleted) {
            try {
                $inventoryService = app(InventoryService::class);
                $inventoryService->deductInventoryForTest($order);
            } catch (\Exception $e) {
                \Log::error("Failed to deduct inventory for test order {$order->id}: ".$e->getMessage());
                // Don't fail the status update if inventory deduction fails
            }

            // Auto-create draft report if results exist and no report exists yet
            $order->refresh();
            $hasResults = $order->testResults()->exists();
            $hasReport = $order->report()->exists();

            if ($hasResults && ! $hasReport) {
                try {
                    $this->autoCreateDraftReport($order);
                    $reportCreated = true;
                } catch (\Exception $e) {
                    \Log::error("Failed to auto-create draft report for test order {$order->id}: ".$e->getMessage());
                    // Don't fail the status update if report creation fails
                }
            }
        }

        // Auto-update to "report_uploaded" when report_status is set to "completed"
        // The workflow service should handle this, but we double-check here
        $order->refresh();
        if ($order->report_status === 'completed' && $order->status === 'completed') {
            $order->update(['status' => 'report_uploaded']);
            $statusUpgraded = true;
        } elseif ($willBeReportUploaded || ($reportStatusCompleted && $oldStatus === 'completed')) {
            $statusUpgraded = true;
        }

        $message = 'Test order status updated successfully.';
        if ($reportCreated) {
            $message .= ' A draft report has been automatically created.';
        }
        if ($statusUpgraded) {
            $message .= ' Order status updated to "Report Uploaded".';
        }

        return redirect()->route('orders.show', $order)
            ->with('success', $message);
    }

    /**
     * Quick action to move workflow forward one step
     */
    public function quickAction(Request $request, TestOrder $order): RedirectResponse
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        $action = $request->input('action');
        $workflowService = app(TestOrderWorkflowService::class);

        $updates = [];
        $message = '';

        switch ($action) {
            case 'collect_sample':
                // Move from pending to sample_collected
                if ($order->status === 'pending') {
                    $transition = $workflowService->applyTransition($order, 'sample_collected', 'collected', null);
                    if ($transition['valid']) {
                        $updates = $transition['updates'];
                        $updates['collected_by'] = Auth::id();
                        $updates['sample_collected_at'] = now();
                        $message = 'Sample collected successfully!';
                    }
                }
                break;

            case 'send_to_lab':
                // Update sample status to sent_to_lab
                if ($order->status === 'sample_collected' || $order->status === 'processing') {
                    $transition = $workflowService->applyTransition($order, $order->status, 'sent_to_lab', null);
                    if ($transition['valid']) {
                        $updates = $transition['updates'];
                        $message = 'Sample sent to lab!';
                    }
                }
                break;

            case 'start_processing':
                // Move from sample_collected to processing
                if ($order->status === 'sample_collected') {
                    $transition = $workflowService->applyTransition($order, 'processing', 'sent_to_lab', null);
                    if ($transition['valid']) {
                        $updates = $transition['updates'];
                        $message = 'Processing started!';
                    }
                }
                break;

            case 'complete_test':
                // Move from processing to completed
                if ($order->status === 'processing') {
                    $transition = $workflowService->applyTransition($order, 'completed', null, null);
                    if ($transition['valid']) {
                        $updates = $transition['updates'];
                        $message = 'Test completed!';

                        // Auto-deduct inventory
                        try {
                            $inventoryService = app(InventoryService::class);
                            $inventoryService->deductInventoryForTest($order);
                        } catch (\Exception $e) {
                            \Log::error('Failed to deduct inventory: '.$e->getMessage());
                        }

                        // Auto-create report if results exist
                        $order->refresh();
                        if ($order->testResults()->exists() && ! $order->report()->exists()) {
                            try {
                                $this->autoCreateDraftReport($order);
                                $message .= ' Draft report created automatically.';
                            } catch (\Exception $e) {
                                \Log::error('Failed to auto-create report: '.$e->getMessage());
                            }
                        }
                    }
                }
                break;

            case 'complete_report':
                // Mark report as completed and move to report_uploaded
                if ($order->status === 'completed' && $order->report()->exists()) {
                    $transition = $workflowService->applyTransition($order, 'completed', null, 'completed');
                    if ($transition['valid']) {
                        $updates = $transition['updates'];
                        $message = 'Report marked as completed!';
                    }
                }
                break;
        }

        if (empty($updates)) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This action is not available in the current workflow state.');
        }

        // Handle sample collection timestamp
        if (isset($updates['sample_status']) && $updates['sample_status'] === 'collected') {
            if (! $order->sample_collected_at) {
                $updates['sample_collected_at'] = now();
                $updates['collected_by'] = Auth::id();
            }
        }

        $order->update($updates);

        // Auto-update to report_uploaded if report is completed
        $order->refresh();
        if ($order->report_status === 'completed' && $order->status === 'completed') {
            $order->update(['status' => 'report_uploaded']);
            $message .= ' Order status updated to "Report Uploaded".';
        }

        return redirect()->route('orders.show', $order)
            ->with('success', $message);
    }

    /**
     * Show result entry form for a test order
     */
    public function enterResults(TestOrder $order)
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        $order->load(['patient', 'test', 'testResults.testParameter', 'report']);

        // Ensure parameters are loaded
        $testResultService = app(TestResultService::class);
        $testResultService->loadParametersForOrder($order);

        // Reload to get fresh results
        $order->load(['testResults.testParameter', 'report']);

        // Sort results by parameter sort_order
        $results = $order->testResults->sortBy(function ($result) {
            return $result->testParameter ? $result->testParameter->sort_order : 999;
        });

        return view('orders.enter-results', compact('order', 'results'));
    }

    /**
     * Save test results
     */
    public function saveResults(Request $request, TestOrder $order): RedirectResponse
    {
        // Ensure order belongs to current lab
        if ($order->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'results' => 'required|array',
            'results.*.result_id' => 'required|exists:test_results,id',
            'results.*.result_value' => 'nullable|string|max:255',
            'next' => 'nullable|in:stay,report',
        ]);

        $testResultService = app(TestResultService::class);

        foreach ($validated['results'] as $resultData) {
            $testResultService->saveResult([
                'result_id' => $resultData['result_id'],
                'result_value' => $resultData['result_value'] ?? '',
            ]);
        }

        // Update order status to processing if results are entered
        if ($order->status === 'pending' || $order->status === 'sample_collected') {
            $order->update(['status' => 'processing']);
        }

        $order->refresh();
        $order->load('report');

        $next = $validated['next'] ?? 'stay';

        if ($next === 'report') {
            if ($order->report) {
                return redirect()->route('reports.show', $order->report)
                    ->with('success', 'Results saved. Opening the report for this order.');
            }

            return redirect()->route('reports.create', $order)
                ->with('success', 'Results saved. Generate or upload the report below.');
        }

        return redirect()->route('orders.enter-results', $order)
            ->with('success', 'Results saved.');
    }

    /**
     * Auto-create a draft report when test is completed and results exist
     */
    protected function autoCreateDraftReport(TestOrder $order): void
    {
        // Reload order with relationships
        $order->load(['patient', 'test', 'testResults.testParameter', 'doctor', 'lab']);

        // Check if report already exists
        if ($order->report()->exists()) {
            return;
        }

        // Check if results exist
        if (! $order->testResults()->exists()) {
            return;
        }

        // Generate report number
        $reportNumber = 'RPT-'.str_pad($order->lab_id, 3, '0', STR_PAD_LEFT).'-'.
                       date('Y').'-'.str_pad(Report::where('lab_id', currentLabId())
                           ->whereYear('created_at', date('Y'))
                           ->count() + 1, 5, '0', STR_PAD_LEFT);

        // Generate PDF report using reflection to access protected method
        $reportController = app(ReportController::class);
        $reflection = new \ReflectionClass($reportController);
        $method = $reflection->getMethod('generatePdfReport');
        $method->setAccessible(true);
        $pdfPath = $method->invoke($reportController, $order, []);

        // Create draft report
        $report = Report::create([
            'lab_id' => $order->lab_id,
            'patient_id' => $order->patient_id,
            'test_order_id' => $order->id,
            'report_name' => $order->test->name.' Report',
            'report_file' => $pdfPath,
            'status' => 'pending',
            'workflow_status' => 'draft',
            'report_number' => $reportNumber,
            'uploaded_by' => Auth::id(),
        ]);

        // Create report_test entry
        ReportTest::create([
            'report_id' => $report->id,
            'test_order_id' => $order->id,
            'test_id' => $order->test_id,
            'sort_order' => 0,
        ]);

        // Update report status to allow completion
        // Note: We don't auto-update to report_uploaded here because the report is still in draft
        // The status will be updated when the report is marked as completed in the workflow
        $order->update([
            'report_status' => 'pending', // Ready to be marked as completed
        ]);
    }
}
