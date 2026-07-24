<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\SalesInvoiceApiController;

Route::prefix('v1')->group(function () {

    // Customers
    Route::get('/customers', [CustomerApiController::class, 'index']);
    Route::get('/customers/{customer}', [CustomerApiController::class, 'show']);

    // Sales Invoices
    Route::get('/sales-invoices', [SalesInvoiceApiController::class, 'index']);
    Route::get('/sales-invoices/{salesInvoice}', [SalesInvoiceApiController::class, 'show']);

    // Accounts Receivable (Finance/Accounting) — protected, kailangan ng API token
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/ar/invoices', [SalesInvoiceApiController::class, 'unpaidInvoices']);
        Route::get('/ar/aging-summary', [SalesInvoiceApiController::class, 'agingSummary']);
    });

});