<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (\App\Services\ClientSaleService $saleService) {
    $user = auth()->user();
    $summary = ['total_assigned' => 0, 'total_sold' => 0, 'remaining_stock' => 0, 'total_revenue' => 0];
    $inventoryBreakdown = collect();
    $recentSales = collect();

    if ($user && $user->hasRole('Client')) {
        $summary = $saleService->getClientSummary($user);
        $inventoryBreakdown = $saleService->getClientInventoryBreakdown($user);
        $recentSales = $saleService->getClientSales($user, [], 5);
    }

    return view('dashboard', [
        'user' => $user,
        'summary' => $summary,
        'inventoryBreakdown' => $inventoryBreakdown,
        'recentSales' => $recentSales,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSalesController;
use App\Http\Controllers\Admin\AdminStockRequestController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientManagementController;
use App\Http\Controllers\Admin\ProductAssignmentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Client\ClientReportController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\SalesController;
use App\Http\Controllers\Client\StockRequestController;

// Client Sales, Payment, Stock Request & Report Routes
Route::middleware(['auth', 'verified', 'role:Client'])->group(function () {
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
});

// Admin Dashboard, Client, Category, Product, Assignment, Stock Request, Sales, Payment & Report Routes
Route::prefix('admin')->middleware(['auth', 'approved', 'role:Super Admin|Admin'])->group(function () {
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
