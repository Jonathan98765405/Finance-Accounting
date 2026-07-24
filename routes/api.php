<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountsPayableController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\PurchaseOrderSyncController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/purchase-orders/sync', [PurchaseOrderSyncController::class, 'store']);
});

// Public Authentication Route
Route::post('/v1/login', [AuthController::class, 'login']);

// Protected Route for Logout (requires a valid token)
Route::middleware('auth:sanctum')->post('/v1/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->prefix('v1/ap')->name('api.v1.ap.')->group(function () {

    // Sync Purchase Order to Bill (AP)
    Route::post('/purchase-orders/sync', [BillController::class, 'store'])->name('po.sync');

    // AP Dashboard
    Route::get('/dashboard', [AccountsPayableController::class, 'apiDashboard'])->name('dashboard');

    // Suppliers
    Route::get('/suppliers', [AccountsPayableController::class, 'supplierIndex'])->name('suppliers.index');

    // Invoices
    Route::get('/invoices', [AccountsPayableController::class, 'invoiceIndex'])->name('invoices.index');

    // Purchase Orders (PO)
    Route::prefix('purchase-orders')->name('po.')->group(function () {
        Route::get('/', [AccountsPayableController::class, 'poIndex'])->name('index');
        Route::post('/', [AccountsPayableController::class, 'poStore'])->name('store');
        Route::get('/{purchaseOrder}', [AccountsPayableController::class, 'poShow'])->name('show');
        Route::patch('/{purchaseOrder}/approve', [AccountsPayableController::class, 'poApprove'])->name('approve');
    });

    // Goods Receipt Notes (GRN)
    Route::prefix('goods-receipts')->name('grn.')->group(function () {
        Route::get('/', [AccountsPayableController::class, 'grnIndex'])->name('index');
        Route::post('/', [AccountsPayableController::class, 'grnStore'])->name('store');
    });

    // Three-Way Matching
    Route::prefix('matching')->name('match.')->group(function () {
        Route::get('/pending', [AccountsPayableController::class, 'pendingMatches'])->name('pending');
        Route::get('/invoices/{invoice}', [AccountsPayableController::class, 'showMatch'])->name('show');
        Route::post('/invoices/{invoice}/approve', [AccountsPayableController::class, 'approveMatch'])->name('approve');
    });

    // Payment Processing
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AccountsPayableController::class, 'paymentIndex'])->name('index');
        Route::post('/{invoice}/process', [AccountsPayableController::class, 'processPayment'])->name('process');
    });

});