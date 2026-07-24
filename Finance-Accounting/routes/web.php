<?php



use App\Http\Controllers\Api\V1\AccountsPayable\AccountsPayableApiController;
use App\Models\AccountReceivable\Reminder;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\AccountsPayableController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\FinancialReportsController;
use App\Http\Controllers\AccountsReceivableController;
use App\Http\Controllers\BudgetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\BillViewController;

Route::get('/bills', [BillViewController::class, 'index'])->name('bills.index');
Route::get('/bills/{bill}', [BillViewController::class, 'show'])->name('bills.show');

use App\Http\Controllers\SalesSyncController;

/**
 * Add these lines to your existing routes/web.php
 * (put them inside your `auth` middleware group if you have one).
 */

Route::get('/account/roles', [AccountController::class, 'roles'])->name('account.roles');
Route::post('/account/switch-role', [AccountController::class, 'switchRole'])->name('account.switch-role');

/*--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| General Ledger
|--------------------------------------------------------------------------
*/

Route::get('/general-ledger', [GeneralLedgerController::class, 'index'])
    ->name('ledger.index');

Route::get('/general-ledger/create', [GeneralLedgerController::class, 'create'])
    ->name('ledger.create');

Route::post('/general-ledger', [GeneralLedgerController::class, 'store'])
    ->name('ledger.store');

Route::get('/general-ledger/{id}', [GeneralLedgerController::class, 'show'])
    ->name('ledger.show');

Route::get('/general-ledger/{id}/edit', [GeneralLedgerController::class, 'edit'])
    ->name('ledger.edit');

Route::put('/general-ledger/{id}', [GeneralLedgerController::class, 'update'])
    ->name('ledger.update');

Route::delete('/general-ledger/{id}', [GeneralLedgerController::class, 'destroy'])
    ->name('ledger.delete');

Route::get('/general-ledger/reports/chart-of-accounts', [GeneralLedgerController::class, 'chartAccounts'])
    ->name('ledger.accounts');

Route::get('/general-ledger/reports/trial-balance', [GeneralLedgerController::class, 'trialBalance'])
    ->name('ledger.trial-balance');

Route::get('/general-ledger/journal/all', [GeneralLedgerController::class, 'journalAll'])
    ->name('ledger.alljournal');

/*
|--------------------------------------------------------------------------
| Financial Reports
|--------------------------------------------------------------------------
*/

Route::get('/financial-reports', [FinancialReportsController::class, 'overview'])
    ->name('financial-reports.overview');

Route::get('/financial-reports/income-balance', [FinancialReportsController::class, 'incomeBalance'])
    ->name('financial-reports.income-balance');

Route::get('/financial-reports/cashflow-tax', [FinancialReportsController::class, 'cashflowTax'])
    ->name('financial-reports.cashflow-tax');

Route::get('/financial-reports/overview', [FinancialReportsController::class, 'overview'])
    ->name('financial-reports.overview');
Route::get('/financial-reports/overview/data', [FinancialReportsController::class, 'overviewData'])
    ->name('financial-reports.overview.data');

Route::get('/financial-reports/income-balance', [FinancialReportsController::class, 'incomeBalance'])
    ->name('financial-reports.income-balance');
Route::get('/financial-reports/income-balance/data', [FinancialReportsController::class, 'incomeBalanceData'])
    ->name('financial-reports.income-balance.data');

Route::get('/financial-reports/cashflow-tax', [FinancialReportsController::class, 'cashflowTax'])
    ->name('financial-reports.cashflow-tax');
Route::get('/financial-reports/cashflow-tax/data', [FinancialReportsController::class, 'cashflowTaxData'])
    ->name('financial-reports.cashflow-tax.data');

Route::get('/financial-reports/budget-actual', [FinancialReportsController::class, 'budgetActual'])
    ->name('financial-reports.budget-actual');

Route::post('/financial-reports/audits', [FinancialReportsController::class, 'storeAudit'])
    ->name('financial-reports.audits.store');

Route::put('/financial-reports/audits/{audit}', [FinancialReportsController::class, 'updateAudit'])
    ->name('financial-reports.audits.update');
 
Route::delete('/financial-reports/audits/{audit}', [FinancialReportsController::class, 'destroyAudit'])
    ->name('financial-reports.audits.destroy');

Route::post('/financial-reports/tax-calendar', [FinancialReportsController::class, 'storeTaxCalendarItem'])
    ->name('financial-reports.tax-calendar.store');
 
Route::put('/financial-reports/tax-calendar/{taxCalendar}', [FinancialReportsController::class, 'updateTaxCalendarItem'])
    ->name('financial-reports.tax-calendar.update');
 
Route::delete('/financial-reports/tax-calendar/{taxCalendar}', [FinancialReportsController::class, 'destroyTaxCalendarItem'])
    ->name('financial-reports.tax-calendar.destroy');

/*
|--------------------------------------------------------------------------
| Accounts Payable
|--------------------------------------------------------------------------
*/

Route::get('/account-payable', [AccountsPayableController::class, 'dashboard'])
    ->name('ap.dashboard');

Route::get('/account-payable/record-invoice', [AccountsPayableController::class, 'createInvoice'])
    ->name('ap.record');

Route::post('/account-payable/record-invoice', [AccountsPayableController::class, 'storeInvoice'])
    ->name('ap.record.store');

Route::get('/account-payable/review-invoice/{invoice?}', [AccountsPayableController::class, 'reviewInvoice'])
    ->name('ap.review');

Route::post('/account-payable/review-invoice/{invoice}/verify', [AccountsPayableController::class, 'verifyInvoice'])
    ->name('ap.review.verify');

Route::post('/account-payable/review-invoice/{invoice}/reject', [AccountsPayableController::class, 'rejectInvoice'])
    ->name('ap.review.reject');

Route::get('/account-payable/three-way-match-queue', [AccountsPayableController::class, 'pendingMatches'])
    ->name('ap.match.pending');

Route::get('/account-payable/three-way-match/{invoice?}', [AccountsPayableController::class, 'threeWayMatch'])
    ->name('ap.match');

Route::post('/account-payable/three-way-match/{invoice}/link-po', [AccountsPayableController::class, 'linkPurchaseOrder'])
    ->name('ap.match.link-po');

Route::post('/account-payable/three-way-match/{invoice}/approve', [AccountsPayableController::class, 'approveMatch'])
    ->name('ap.match.approve');

Route::post('/account-payable/three-way-match/{invoice}/draft', [AccountsPayableController::class, 'saveMatchDraft'])
    ->name('ap.match.draft');

Route::post('/account-payable/three-way-match/{invoice}/clarify', [AccountsPayableController::class, 'requestMatchClarification'])
    ->name('ap.match.clarify');

Route::get('/account-payable/purchase-orders', [AccountsPayableController::class, 'purchaseOrders'])
    ->name('ap.po.index');

Route::get('/account-payable/purchase-orders/create', [AccountsPayableController::class, 'createPurchaseOrder'])
    ->name('ap.po.create');

Route::post('/account-payable/purchase-orders', [AccountsPayableController::class, 'storePurchaseOrder'])
    ->name('ap.po.store');

Route::get('/account-payable/purchase-orders/{purchaseOrder}/edit', [AccountsPayableController::class, 'editPurchaseOrder'])
    ->name('ap.po.edit');

Route::put('/account-payable/purchase-orders/{purchaseOrder}', [AccountsPayableController::class, 'updatePurchaseOrder'])
    ->name('ap.po.update');

Route::delete('/account-payable/purchase-orders/{purchaseOrder}', [AccountsPayableController::class, 'destroyPurchaseOrder'])
    ->name('ap.po.destroy');    

Route::get('/account-payable/goods-receipts/create/{purchaseOrder?}', [AccountsPayableController::class, 'createGoodsReceipt'])
    ->name('ap.grn.create');

Route::post('/account-payable/goods-receipts', [AccountsPayableController::class, 'storeGoodsReceipt'])
    ->name('ap.grn.store');

Route::get('/account-payable/schedule-payment', [AccountsPayableController::class, 'schedulePayment'])
    ->name('ap.schedule');

Route::post('/account-payable/schedule-payment/{invoice}', [AccountsPayableController::class, 'storeSchedule'])
    ->name('ap.schedule.store');

Route::get('/account-payable/payment-processing', [AccountsPayableController::class, 'paymentProcessing'])
    ->name('ap.payment');

Route::post('/account-payable/payment-processing/{payment}/process', [AccountsPayableController::class, 'processPayment'])
    ->name('ap.payment.process');

Route::get('/account-payable/payment-processing/{payment}/remittance', [AccountsPayableController::class, 'downloadRemittance'])
    ->name('ap.payment.remittance');

Route::post('/account-payable/payment-processing/{payment}/email-remittance', [AccountsPayableController::class, 'emailRemittance'])
    ->name('ap.payment.remittance.email');



    /*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1/ap')->group(function () {
    Route::get('/suppliers', [AccountsPayableApiController::class, 'suppliers']);
    Route::get('/purchase-orders', [AccountsPayableApiController::class, 'purchaseOrders']);
    Route::get('/invoices', [AccountsPayableApiController::class, 'invoices']);
    Route::get('/payments', [AccountsPayableApiController::class, 'payments']);
    Route::get('/dashboard', [AccountsPayableApiController::class, 'dashboard']);
});



/*
|--------------------------------------------------------------------------
| Fixed Assets
|--------------------------------------------------------------------------
*/

Route::get('/fixed-assets', [FixedAssetController::class, 'index'])
    ->name('fixed-assets.index');

Route::get('/fixed-assets/register', [FixedAssetController::class, 'create'])
    ->name('fixed-assets.create');

Route::post('/fixed-assets/register', [FixedAssetController::class, 'store'])
    ->name('fixed-assets.store');

Route::get('/fixed-assets/assignment/{id?}', [FixedAssetController::class, 'assignment'])
    ->name('fixed-assets.assignment');

Route::get('/fixed-assets/edit/{id}', [FixedAssetController::class, 'edit'])
    ->name('fixed-assets.edit');

Route::post('/fixed-assets/edit/{id}', [FixedAssetController::class, 'update'])
    ->name('fixed-assets.update');

Route::post('/fixed-assets/delete/{id}', [FixedAssetController::class, 'destroy'])
    ->name('fixed-assets.destroy');
Route::get('/fixed-assets/dispose/{id}', [FixedAssetController::class, 'disposeForm']);
Route::post('/fixed-assets/dispose/{id}', [FixedAssetController::class, 'dispose']);    

 /*
|--------------------------------------------------------------------------
| Account Receivable
|--------------------------------------------------------------------------
*/
 
// Dashboard
Route::get('/accounts-receivable', [AccountsReceivableController::class, 'dashboard'])
    ->name('receivable.dashboard');
 
// Invoice - Create page + Save
Route::get('/accounts-receivable/invoice', [AccountsReceivableController::class, 'invoice'])
    ->name('receivable.invoice');
 
Route::post('/accounts-receivable/invoice', [AccountsReceivableController::class, 'storeInvoice'])
    ->name('receivable.invoice.store');
 
// Invoice - List page
Route::get('/accounts-receivable/allinvoices', [AccountsReceivableController::class, 'allInvoices'])
    ->name('receivable.allinvoices');
 
// Invoice - Details (JSON), View, Update, Delete
Route::get('/accounts-receivable/invoice/{id}/details', [AccountsReceivableController::class, 'details'])
    ->name('receivable.invoice.details');
 
Route::get('/accounts-receivable/invoice/{id}', [AccountsReceivableController::class, 'show'])
    ->name('receivable.invoice.show');
 
Route::put('/accounts-receivable/invoice/{id}', [AccountsReceivableController::class, 'update'])
    ->name('receivable.invoice.update');
 
Route::delete('/accounts-receivable/invoice/{id}', [AccountsReceivableController::class, 'destroy'])
    ->name('receivable.invoice.destroy');
 
// Customer's open invoices (used when recording a payment)
Route::get('/accounts-receivable/customer/{id}/invoices', [AccountsReceivableController::class, 'customerInvoices'])
    ->name('receivable.customer.invoices');
 
// Payment - Create page + Save
Route::get('/accounts-receivable/payment', [AccountsReceivableController::class, 'payment'])
    ->name('receivable.payment');
 
Route::post('/accounts-receivable/payment', [AccountsReceivableController::class, 'storePayment'])
    ->name('receivable.payment.store');
 
// Aging Report - page + exports
Route::get('/accounts-receivable/aging', [AccountsReceivableController::class, 'aging'])
    ->name('receivable.aging');
 
Route::get('/accounts-receivable/aging/export/excel', [AccountsReceivableController::class, 'exportExcel'])
    ->name('receivable.aging.export.excel');
 
Route::get('/accounts-receivable/aging/export/pdf', [AccountsReceivableController::class, 'exportPdf'])
    ->name('receivable.aging.export.pdf');
 
// Reminders

Route::post('/accounts-receivable/reminder', [AccountsReceivableController::class, 'storeReminder'])
    ->name('receivable.reminder.store');

Route::get('/accounts-receivable/reminder/history', [AccountsReceivableController::class, 'reminderHistory']);
 
// Reports - Generate + Export
Route::get('/accounts-receivable/report', [AccountsReceivableController::class, 'generateReport'])
    ->name('receivable.report.generate');
 
Route::get('/accounts-receivable/report/export/pdf', [AccountsReceivableController::class, 'exportReportPdf'])
    ->name('receivable.report.export.pdf');

//Record peyment
Route::get('/accounts-receivable/payment', [AccountsReceivableController::class, 'payment'])
    ->name('receivable.payment');
 
Route::post('/accounts-receivable/payment', [AccountsReceivableController::class, 'storePayment'])
    ->name('receivable.storePayment');
 
Route::get('/accounts-receivable/customers/{id}/invoices', [AccountsReceivableController::class, 'customerInvoices'])
    ->name('receivable.customerInvoices');


Route::post('/accounts-receivable/sync', [SalesSyncController::class, 'sync'])
    ->name('receivable.sync');

/*
|--------------------------------------------------------------------------
| Budget Forecasting
|--------------------------------------------------------------------------
*/

// Explicit path to access the budget interface
Route::get('/budget', function () {
    return view('budget-forecasting.budget');
})->name('budget.view');

// Data endpoints matching your frontend JavaScript fetch requests
Route::get('/budgets', [BudgetController::class, 'index'])->name('budget.data');
Route::post('/budgets/update', [BudgetController::class, 'update'])->name('budget.update');