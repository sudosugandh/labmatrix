<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebsiteSettingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\FrontendPageController;
use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PublicAppointmentController;
use App\Http\Controllers\AppointmentSlotController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TestPackageController;
use App\Http\Controllers\PublicPackageController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\ReportBuilderController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryAdjustmentController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\TestConsumableController;
use App\Http\Controllers\DiagnosticReportTemplateController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeVisitRequestController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PublicBillPaymentController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

// Auth Routes (must be before dynamic slug route)
require __DIR__.'/auth.php';

// Public Website Routes
Route::get('/', [HomepageController::class, 'index'])->name('home');

// Public Report Portal (must be before dynamic slug route and admin routes)
// Note: These routes must be defined before admin routes to avoid conflicts
Route::get('/patient-reports', [PublicReportController::class, 'search'])->name('public.reports.search');
Route::post('/patient-reports', [PublicReportController::class, 'results'])->name('public.reports.results');
Route::get('/patient-reports/{id}/download', [PublicReportController::class, 'download'])
    ->where('id', '[0-9]+')
    ->name('public.reports.download');
// GET: bookmark/refresh hits this URL — redirect (POST-only would otherwise 404)
Route::get('/patient-reports/email-link', function () {
    return redirect()
        ->route('public.reports.search')
        ->with('info', 'Search for your reports first, then use “Email link” on a report to receive a download link.');
})->name('public.reports.email-link.get');
Route::post('/patient-reports/email-link', [PublicReportController::class, 'sendReportLinkByEmail'])
    ->name('public.reports.email-link');

// Public Packages Display (must be before dynamic slug route and admin routes)
Route::get('/packages', [PublicPackageController::class, 'index'])->name('public.packages.index');
Route::get('/packages/{testPackage}', [PublicPackageController::class, 'show'])
    ->where('testPackage', '[0-9]+')
    ->name('public.packages.show');

// Public Appointment Booking (must be before dynamic slug route and admin routes)
Route::get('/book-appointment', [PublicAppointmentController::class, 'create'])->name('public.appointments.create');
Route::get('/book-appointment/available-slots', [PublicAppointmentController::class, 'availableSlots'])->name('public.appointments.available-slots');
Route::post('/book-appointment', [PublicAppointmentController::class, 'store'])->name('public.appointments.store');
Route::get('/appointment-confirmation/{appointment}', [PublicAppointmentController::class, 'confirmation'])
    ->where('appointment', '[0-9]+')
    ->name('public.appointments.confirmation');
Route::get('/my-appointments', [PublicAppointmentController::class, 'search'])->name('public.appointments.search');
Route::post('/my-appointments', [PublicAppointmentController::class, 'results'])->name('public.appointments.results');
Route::get('/my-appointments/{appointment}', [PublicAppointmentController::class, 'show'])
    ->where('appointment', '[0-9]+')
    ->name('public.appointments.show');
Route::get('/my-appointments/{appointment}/print', [PublicAppointmentController::class, 'print'])
    ->where('appointment', '[0-9]+')
    ->name('public.appointments.print');
Route::delete('/my-appointments/{appointment}', [PublicAppointmentController::class, 'cancel'])
    ->where('appointment', '[0-9]+')
    ->name('public.appointments.cancel');

// Home visit / home collection lead popup
Route::post('/home-visit-request', [HomeVisitRequestController::class, 'store'])->name('public.home-visit-request.store');

// Public payment link (pay bill by token - no auth)
Route::get('/pay-bill/{token}', [PublicBillPaymentController::class, 'show'])->name('public.bill-pay.show');
Route::post('/pay-bill/create-razorpay-order', [PublicBillPaymentController::class, 'createRazorpayOrder'])->name('public.bill-pay.create-razorpay');
Route::post('/pay-bill/verify-razorpay', [PublicBillPaymentController::class, 'verifyRazorpay'])->name('public.bill-pay.verify-razorpay');
Route::post('/pay-bill/create-stripe-session', [PublicBillPaymentController::class, 'createStripeSession'])->name('public.bill-pay.create-stripe');
Route::get('/pay-bill/{token}/success', [PublicBillPaymentController::class, 'success'])->name('public.bill-pay.success');
Route::get('/pay-bill/{token}/stripe-success', [PublicBillPaymentController::class, 'stripeSuccess'])->name('public.bill-pay.stripe-success');

// Admin Routes (Authenticated) - Must be before dynamic slug route
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Settings (Comprehensive Settings System)
    Route::get('/settings', [WebsiteSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [WebsiteSettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/banner/{index}', [WebsiteSettingController::class, 'deleteBanner'])->name('settings.delete-banner');
    
    // Legacy route for backward compatibility
    Route::get('/website-settings', [WebsiteSettingController::class, 'index'])->name('website-settings.edit');
    Route::put('/website-settings', [WebsiteSettingController::class, 'update'])->name('website-settings.update');
    
    // CMS Pages
    Route::resource('pages', PageController::class);
    Route::post('/cms/upload-image', [\App\Http\Controllers\CmsImageController::class, 'store'])->name('cms.upload-image');
    
    // Menus
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::post('/menus/{menu}/items', [MenuController::class, 'addItem'])->name('menus.items.store');
    Route::put('/menus/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
    Route::delete('/menus/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
    Route::post('/menus/items/reorder', [MenuController::class, 'updateItemOrder'])->name('menus.items.reorder');
});

// Super Admin Routes
Route::middleware(['auth', 'superadmin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // Labs Management
    Route::get('/labs', [SuperAdminController::class, 'labs'])->name('labs.index');
    Route::get('/labs/create', [SuperAdminController::class, 'createLab'])->name('labs.create');
    Route::post('/labs', [SuperAdminController::class, 'storeLab'])->name('labs.store');
    Route::get('/labs/export', [SuperAdminController::class, 'exportLabs'])->name('labs.export');
    Route::get('/labs/{lab}', [SuperAdminController::class, 'showLab'])->name('labs.show');
    Route::get('/labs/{lab}/edit', [SuperAdminController::class, 'editLab'])->name('labs.edit');
    Route::put('/labs/{lab}', [SuperAdminController::class, 'updateLab'])->name('labs.update');
    Route::delete('/labs/{lab}', [SuperAdminController::class, 'destroyLab'])->name('labs.destroy');
    Route::patch('/labs/{lab}/level', [SuperAdminController::class, 'updateLabLevel'])->name('labs.update-level');
    Route::post('/labs/{lab}/suspend', [SuperAdminController::class, 'suspendLab'])->name('labs.suspend');
    Route::post('/labs/{lab}/activate', [SuperAdminController::class, 'activateLab'])->name('labs.activate');
    
    // Domain Management
    Route::post('/labs/{lab}/domains', [SuperAdminController::class, 'storeDomain'])->name('labs.domains.store');
    Route::put('/labs/{lab}/domains/{domain}', [SuperAdminController::class, 'updateDomain'])->name('labs.domains.update');
    Route::delete('/labs/{lab}/domains/{domain}', [SuperAdminController::class, 'destroyDomain'])->name('labs.domains.destroy');
    
    // User Management
    Route::post('/labs/{lab}/users', [SuperAdminController::class, 'storeUser'])->name('labs.users.store');
    Route::put('/labs/{lab}/users/{user}', [SuperAdminController::class, 'updateUser'])->name('labs.users.update');
    Route::delete('/labs/{lab}/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('labs.users.destroy');
    
    // Lab Settings & Features
    Route::put('/labs/{lab}/settings', [SuperAdminController::class, 'updateLabSettings'])->name('labs.settings.update');
    Route::put('/labs/{lab}/features', [SuperAdminController::class, 'updateLabFeatures'])->name('labs.features.update');
    
    // Lab Subscription & Billing
    Route::put('/labs/{lab}/subscription', [SuperAdminController::class, 'updateLabSubscription'])->name('labs.subscription.update');
    
    // Lab Support Notes
    Route::post('/labs/{lab}/support-notes', [SuperAdminController::class, 'storeSupportNote'])->name('labs.support-notes.store');
    Route::delete('/labs/{lab}/support-notes/{note}', [SuperAdminController::class, 'destroySupportNote'])->name('labs.support-notes.destroy');
    
    // Lab Data Export
    Route::get('/labs/{lab}/export-data', [SuperAdminController::class, 'exportLabData'])->name('labs.export-data');
});

// Admin Dashboard Routes (Authenticated) - Must be before dynamic slug route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Patients
    Route::resource('patients', PatientController::class);
    
    // Tests
    Route::resource('tests', TestController::class)->except(['show']);
    Route::post('/tests/{test}/parameters', [TestController::class, 'storeParameter'])->name('tests.parameters.store');
    Route::put('/tests/{test}/parameters/{parameter}', [TestController::class, 'updateParameter'])->name('tests.parameters.update');
    Route::delete('/tests/{test}/parameters/{parameter}', [TestController::class, 'destroyParameter'])->name('tests.parameters.destroy');
    
    // Doctors
    Route::resource('doctors', DoctorController::class);
    Route::get('/doctors/{doctor}/commissions', [DoctorController::class, 'commissions'])->name('doctors.commissions');
    Route::post('/doctor-commissions/{commission}/pay', [DoctorController::class, 'payCommission'])->name('doctor-commissions.pay');
    
    // Test Packages
    Route::resource('test-packages', TestPackageController::class);
    
    // Technicians (only for large labs)
    Route::resource('technicians', TechnicianController::class);
    
    // Test Orders
    Route::resource('orders', TestOrderController::class);
    Route::patch('/orders/{order}/status', [TestOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/quick-action', [TestOrderController::class, 'quickAction'])->name('orders.quick-action');
    Route::get('/orders/{order}/enter-results', [TestOrderController::class, 'enterResults'])->name('orders.enter-results');
    Route::post('/orders/{order}/save-results', [TestOrderController::class, 'saveResults'])->name('orders.save-results');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create/{order}', [ReportController::class, 'create'])->name('reports.create');
    Route::get('/reports/preview/{order}', [ReportController::class, 'preview'])->name('reports.preview');
    Route::post('/reports/{order}', [ReportController::class, 'store'])->name('reports.store');
    Route::post('/reports/multi-test', [ReportController::class, 'createMultiTest'])->name('reports.create-multi-test');
    Route::put('/reports/{report}/workflow', [ReportController::class, 'updateWorkflow'])->name('reports.update-workflow');
    Route::put('/reports/{report}/doctor-signature', [ReportController::class, 'updateDoctorSignature'])->name('reports.update-doctor-signature');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
    
    // Diagnostic Report Templates
    Route::resource('report-templates', DiagnosticReportTemplateController::class);
    Route::post('/report-templates/{reportTemplate}/sections', [DiagnosticReportTemplateController::class, 'storeSection'])->name('report-templates.sections.store');
    Route::get('/report-templates/{reportTemplate}/sections/{section}', [DiagnosticReportTemplateController::class, 'showSection'])->name('report-templates.sections.show');
    Route::put('/report-templates/{reportTemplate}/sections/{section}', [DiagnosticReportTemplateController::class, 'updateSection'])->name('report-templates.sections.update');
    Route::delete('/report-templates/{reportTemplate}/sections/{section}', [DiagnosticReportTemplateController::class, 'destroySection'])->name('report-templates.sections.destroy');
    Route::post('/report-templates/{reportTemplate}/reorder-sections', [DiagnosticReportTemplateController::class, 'reorderSections'])->name('report-templates.reorder-sections');
    
    // Testimonials, FAQ, Promos (CMS)
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::resource('promos', PromoController::class)->except(['show']);
    
    // Bills & Payments
    Route::resource('bills', BillController::class);
    Route::get('/bills/{bill}/print', [BillController::class, 'print'])->name('bills.print');
    Route::post('/bills/{bill}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    
    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::resource('appointment-slots', AppointmentSlotController::class)->except(['show']);
    
    // Inventory Management (Simplified for Diagnostic Labs)
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Inventory Items
        Route::resource('items', InventoryItemController::class);
        Route::post('/items/{item}/adjust-stock', [InventoryItemController::class, 'adjustStock'])->name('items.adjust-stock');
        
        // Stock Adjustments
        Route::get('/adjustments', [InventoryAdjustmentController::class, 'index'])->name('adjustments.index');
        
        // Inventory Dashboard
        Route::get('/dashboard', [InventoryItemController::class, 'dashboard'])->name('dashboard');
        
        // Inventory Reports & Analytics
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [InventoryReportController::class, 'index'])->name('index');
            Route::get('/usage', [InventoryReportController::class, 'usage'])->name('usage');
            Route::get('/trends', [InventoryReportController::class, 'trends'])->name('trends');
            Route::get('/cost-analysis', [InventoryReportController::class, 'costAnalysis'])->name('cost-analysis');
            Route::get('/reorder', [InventoryReportController::class, 'reorderRecommendations'])->name('reorder');
            Route::get('/expiry', [InventoryReportController::class, 'expiryTracking'])->name('expiry');
            Route::get('/export', [InventoryReportController::class, 'export'])->name('export');
        });
    });
    
    // Test Consumables
    Route::get('/tests/{test}/consumables', [TestConsumableController::class, 'index'])->name('tests.consumables.index');
    Route::post('/tests/{test}/consumables', [TestConsumableController::class, 'store'])->name('tests.consumables.store');
    Route::delete('/test-consumables/{consumable}', [TestConsumableController::class, 'destroy'])->name('tests.consumables.destroy');
    
    // Analytics & Reports
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    
    // Export Routes
    Route::get('/export/patients', [ExportController::class, 'exportPatients'])->name('export.patients');
    Route::get('/export/test-orders', [ExportController::class, 'exportTestOrders'])->name('export.test-orders');
    Route::get('/export/appointments', [ExportController::class, 'exportAppointments'])->name('export.appointments');
    Route::get('/export/bills', [ExportController::class, 'exportBills'])->name('export.bills');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/read/all', [NotificationController::class, 'deleteAllRead'])->name('notifications.delete-all-read');
    
    // Report Builder
    Route::get('/report-builder', [ReportBuilderController::class, 'index'])->name('report-builder.index');
    Route::get('/report-builder/create', [ReportBuilderController::class, 'create'])->name('report-builder.create');
    Route::post('/report-builder', [ReportBuilderController::class, 'store'])->name('report-builder.store');
    Route::get('/report-builder/{template}/edit', [ReportBuilderController::class, 'edit'])->name('report-builder.edit');
    Route::put('/report-builder/{template}', [ReportBuilderController::class, 'update'])->name('report-builder.update');
    Route::delete('/report-builder/{template}', [ReportBuilderController::class, 'destroy'])->name('report-builder.destroy');
    Route::get('/report-builder/{template}/load', [ReportBuilderController::class, 'load'])->name('report-builder.load');
    Route::post('/report-builder/preview', [ReportBuilderController::class, 'preview'])->name('report-builder.preview');
    Route::post('/report-builder/export', [ReportBuilderController::class, 'export'])->name('report-builder.export');
    
    // Scheduled Reports
    Route::get('/report-builder/scheduled', [ReportBuilderController::class, 'scheduled'])->name('report-builder.scheduled');
    Route::get('/report-builder/scheduled/create', [ReportBuilderController::class, 'createScheduled'])->name('report-builder.scheduled.create');
    Route::post('/report-builder/scheduled', [ReportBuilderController::class, 'storeScheduled'])->name('report-builder.scheduled.store');
    Route::post('/report-builder/scheduled/{scheduledReport}/toggle', [ReportBuilderController::class, 'toggleScheduled'])->name('report-builder.scheduled.toggle');
    Route::delete('/report-builder/scheduled/{scheduledReport}', [ReportBuilderController::class, 'destroyScheduled'])->name('report-builder.scheduled.destroy');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dynamic CMS Pages Route (must be LAST to catch remaining routes)
Route::get('/{slug}', [FrontendPageController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('page.show');