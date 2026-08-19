<?php

use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminQuotationController;
use App\Http\Controllers\Admin\AdminSalesController;
use App\Http\Controllers\Admin\AdminStockRequestController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientManagementController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\ProductAssignmentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Client\ClientQuotationController;
use App\Http\Controllers\Client\ClientReportController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\SalesController;
use App\Http\Controllers\Client\StockRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Ref\RefAnnouncementController;
use App\Http\Controllers\Ref\RefClientController;
use App\Http\Controllers\Ref\RefClientCrmController;
use App\Http\Controllers\Ref\RefDashboardController;
use App\Http\Controllers\Ref\RefNotificationController;
use App\Http\Controllers\Ref\RefPaymentController;
use App\Http\Controllers\Ref\RefProductController;
use App\Http\Controllers\Ref\RefProfileController;
use App\Http\Controllers\Ref\RefSalesController;
use App\Http\Controllers\Ref\RefStockRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Central Dashboard Redirection Route based on users.role
Route::get('/dashboard', function (\App\Services\ClientSaleService $saleService) {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    if ($user->isAdmin() || $user->hasRole('Super Admin') || $user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isRef() || $user->hasRole('Ref')) {
        return redirect()->route('ref.dashboard');
    }

    $summary = $saleService->getClientSummary($user);
    $inventoryBreakdown = $saleService->getClientInventoryBreakdown($user);
    $recentSales = $saleService->getClientSales($user, [], 5);
    $stockRequests = \App\Models\StockRequest::with(['product', 'reviewer', 'client.salesRep'])
        ->where('client_id', $user->id)
        ->latest()
        ->take(10)
        ->get();

    return view('dashboard', [
        'user' => $user,
        'summary' => $summary,
        'inventoryBreakdown' => $inventoryBreakdown,
        'recentSales' => $recentSales,
        'stockRequests' => $stockRequests,
    ]);
})->middleware(['auth', 'verified', 'approved'])->name('dashboard');

// Client Sales, Payment, Stock Request & Report Routes
Route::middleware(['auth', 'verified', 'role.user:client'])->group(function () {
    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/create', [SalesController::class, 'create'])->name('sales.create');
        Route::post('/', [SalesController::class, 'store'])->name('sales.store');
        Route::get('/reports', [SalesController::class, 'reports'])->name('sales.reports');
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });

    Route::prefix('stock-requests')->group(function () {
        Route::get('/', [StockRequestController::class, 'index'])->name('stock-requests.index');
        Route::get('/create', [StockRequestController::class, 'create'])->name('stock-requests.create');
        Route::post('/', [StockRequestController::class, 'store'])->name('stock-requests.store');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', [ClientReportController::class, 'index'])->name('reports.index');
        Route::get('/export-csv', [ClientReportController::class, 'exportCsv'])->name('reports.export-csv');
        Route::get('/export-excel', [ClientReportController::class, 'exportExcel'])->name('reports.export-excel');
        Route::get('/export-pdf', [ClientReportController::class, 'exportPdf'])->name('reports.export-pdf');
    });

    Route::prefix('quotations')->group(function () {
        Route::get('/', [ClientQuotationController::class, 'index'])->name('client.quotations.index');
        Route::get('/{quotation}', [ClientQuotationController::class, 'show'])->name('client.quotations.show');
        Route::get('/{quotation}/pdf', [ClientQuotationController::class, 'downloadPdf'])->name('client.quotations.pdf');
        Route::get('/{quotation}/print', [ClientQuotationController::class, 'print'])->name('client.quotations.print');
    });
});

// Reference (REF) Role Routes (Sales Representative Portal)
Route::prefix('ref')->middleware(['auth', 'approved', 'role.user:ref'])->group(function () {
    // Reference CRM Client Workspace Routes
    Route::get('/dashboard', [RefDashboardController::class, 'index'])->name('ref.dashboard');
    Route::get('/workspace/client/{client}', [RefClientCrmController::class, 'showAjax'])->name('ref.workspace.client');
    Route::post('/workspace/client/{client}/payments', [RefClientCrmController::class, 'storePayment'])->name('ref.workspace.payments.store');
    Route::post('/workspace/client/{client}/stock-requests', [RefClientCrmController::class, 'storeStockRequest'])->name('ref.workspace.stock-requests.store');
    Route::post('/workspace/client/{client}/notes', [RefClientCrmController::class, 'storeNote'])->name('ref.workspace.notes.store');
    Route::get('/workspace/client/{client}/print', [RefClientCrmController::class, 'printSummary'])->name('ref.workspace.client.print');

    // Assigned Clients
    Route::get('/clients', [RefClientController::class, 'index'])->name('ref.clients.index');
    Route::get('/clients/{client}', [RefClientController::class, 'show'])->name('ref.clients.show');

    // Assigned Products & Availability
    Route::get('/products', [RefProductController::class, 'index'])->name('ref.products.index');

    // Submit product stock requests to Admin
    Route::get('/stock-requests', [RefStockRequestController::class, 'index'])->name('ref.stock-requests.index');
    Route::get('/stock-requests/create', [RefStockRequestController::class, 'create'])->name('ref.stock-requests.create');
    Route::post('/stock-requests', [RefStockRequestController::class, 'store'])->name('ref.stock-requests.store');

    // Sales history & reports
    Route::get('/sales', [RefSalesController::class, 'index'])->name('ref.sales.index');
    Route::get('/sales/reports', [RefSalesController::class, 'reports'])->name('ref.sales.reports');

    // Payment status tracking
    Route::get('/payments', [RefPaymentController::class, 'index'])->name('ref.payments.index');
    Route::get('/payments/{payment}', [RefPaymentController::class, 'show'])->name('ref.payments.show');

    // Notifications
    Route::get('/notifications', [RefNotificationController::class, 'index'])->name('ref.notifications.index');
    Route::post('/notifications/read-all', [RefNotificationController::class, 'markAllAsRead'])->name('ref.notifications.read-all');

    // Announcements
    Route::get('/announcements', [RefAnnouncementController::class, 'index'])->name('ref.announcements.index');

    // Ref Profile & Password management
    Route::get('/profile', [RefProfileController::class, 'edit'])->name('ref.profile.edit');
    Route::patch('/profile', [RefProfileController::class, 'update'])->name('ref.profile.update');
    Route::put('/profile/password', [RefProfileController::class, 'updatePassword'])->name('ref.profile.password');
});

// Admin Dashboard, Client, Category, Product, Assignment, Stock Request, Sales, Payment & Report Routes
Route::prefix('admin')->middleware(['auth', 'approved', 'role.user:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin Stock Requests Review
    Route::prefix('stock-requests')->group(function () {
        Route::get('/', [AdminStockRequestController::class, 'index'])->name('admin.stock-requests.index');
        Route::patch('/{stockRequest}/approve', [AdminStockRequestController::class, 'approve'])->name('admin.stock-requests.approve');
        Route::patch('/{stockRequest}/reject', [AdminStockRequestController::class, 'reject'])->name('admin.stock-requests.reject');
    });

    // Admin Master Reports & Analytics
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/export-csv', [ReportController::class, 'exportCsv'])->name('admin.reports.export-csv');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
    });

    // Admin Payment Verification Routes
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('admin.payments.show');
    Route::patch('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::patch('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');

    // Admin Global Sales History & Master Reports
    Route::get('/sales', [AdminSalesController::class, 'index'])->name('admin.sales.index');
    Route::get('/sales/reports', [AdminSalesController::class, 'reports'])->name('admin.sales.reports');

    // Complete Client Management Module Routes
    Route::get('/clients', [ClientManagementController::class, 'index'])->name('admin.clients.index');
    Route::get('/clients/create', [ClientManagementController::class, 'create'])->name('admin.clients.create');
    Route::post('/clients', [ClientManagementController::class, 'store'])->name('admin.clients.store');
    Route::get('/clients/{client}', [ClientManagementController::class, 'show'])->name('admin.clients.show');
    Route::get('/clients/{client}/edit', [ClientManagementController::class, 'edit'])->name('admin.clients.edit');
    Route::put('/clients/{client}', [ClientManagementController::class, 'update'])->name('admin.clients.update');
    Route::delete('/clients/{client}', [ClientManagementController::class, 'destroy'])->name('admin.clients.destroy');

    // Client Status Actions
    Route::patch('/clients/{client}/approve', [ClientManagementController::class, 'approve'])->name('admin.clients.approve');
    Route::patch('/clients/{client}/reject', [ClientManagementController::class, 'reject'])->name('admin.clients.reject');
    Route::patch('/clients/{client}/activate', [ClientManagementController::class, 'activate'])->name('admin.clients.activate');
    Route::patch('/clients/{client}/deactivate', [ClientManagementController::class, 'deactivate'])->name('admin.clients.deactivate');

    // Category Management Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Product Management Module Routes
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('admin.products.adjust-stock');

    // Product Assignment Module Routes
    Route::get('/product-assignments', [ProductAssignmentController::class, 'index'])->name('admin.product-assignments.index');
    Route::get('/product-assignments/create', [ProductAssignmentController::class, 'create'])->name('admin.product-assignments.create');
    Route::post('/product-assignments', [ProductAssignmentController::class, 'store'])->name('admin.product-assignments.store');
    Route::get('/product-assignments/{productAssignment}', [ProductAssignmentController::class, 'show'])->name('admin.product-assignments.show');

    // Quotation Management Module Routes
    Route::prefix('quotations')->group(function () {
        Route::get('/dashboard', [AdminQuotationController::class, 'dashboard'])->name('admin.quotations.dashboard');
        Route::get('/', [AdminQuotationController::class, 'index'])->name('admin.quotations.index');
        Route::get('/create', [AdminQuotationController::class, 'create'])->name('admin.quotations.create');
        Route::post('/', [AdminQuotationController::class, 'store'])->name('admin.quotations.store');

        Route::get('/settings', [CompanySettingController::class, 'edit'])->name('admin.quotations.settings.edit');
        Route::put('/settings', [CompanySettingController::class, 'update'])->name('admin.quotations.settings.update');

        Route::get('/{quotation}', [AdminQuotationController::class, 'show'])->name('admin.quotations.show');
        Route::get('/{quotation}/edit', [AdminQuotationController::class, 'edit'])->name('admin.quotations.edit');
        Route::put('/{quotation}', [AdminQuotationController::class, 'update'])->name('admin.quotations.update');
        Route::delete('/{quotation}', [AdminQuotationController::class, 'destroy'])->name('admin.quotations.destroy');

        Route::get('/{quotation}/print', [AdminQuotationController::class, 'print'])->name('admin.quotations.print');
        Route::get('/{quotation}/pdf', [AdminQuotationController::class, 'downloadPdf'])->name('admin.quotations.pdf');
        Route::post('/{quotation}/email', [AdminQuotationController::class, 'sendEmail'])->name('admin.quotations.email');
        Route::post('/{quotation}/duplicate', [AdminQuotationController::class, 'duplicate'])->name('admin.quotations.duplicate');
    });

    Route::get('/test-flash/{type}', function ($type) {
        $messages = [
            'success' => 'System configuration successfully updated!',
            'error' => 'Failed to synchronize repository changes.',
            'warning' => 'Storage disk quota reaching limit (85%).',
            'info' => 'New updates are available for Ceylon AG.'
        ];
        
        $msg = $messages[$type] ?? 'Test notification triggered.';
        flash_message($msg, $type);
        
        return redirect()->route('admin.dashboard');
    })->name('admin.test-flash');
});

// Error Page Previews
Route::get('/test-error/403', fn() => response()->view('errors.403', [], 403));
Route::get('/test-error/404', fn() => response()->view('errors.404', [], 404));
Route::get('/test-error/500', fn() => response()->view('errors.500', [], 500));

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
