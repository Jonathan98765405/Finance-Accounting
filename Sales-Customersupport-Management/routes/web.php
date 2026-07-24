<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesInvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Backed by real Eloquent models + a database now — Customer and
| SalesInvoice records are persisted, searched, filtered, and paginated
| through the controllers below instead of static/mock arrays.
*/

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('customers', CustomerController::class);

Route::resource('sales-invoices', SalesInvoiceController::class);