<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ApprovalController;
use App\Models\User;

// Local Development Auto-Login Helper
if (app()->environment('local')) {
    Route::get('/dev-login', function () {
        $user = User::first();

        if (! $user) {
            $user = User::create([
                'name'     => 'Dev Admin',
                'email'    => 'dev@local.test',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]);
        }

        auth()->login($user);

        return redirect('/')->with('success', "Logged in as {$user->email} (dev mode).");
    })->name('dev-login');
}

// Dummy Logout Route to satisfy the layout button without crashing
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Public Procurement Routes
Route::get('/', function () {
    return view('procurement.dashboard');
})->name('dashboard');

Route::resource('vendors', VendorController::class)->only(['index', 'store']);

Route::resource('requisitions', RequisitionController::class)->only(['index', 'store']);
Route::post('requisitions/{requisition}/convert', [PurchaseOrderController::class, 'convertFromRequisition'])
    ->name('requisitions.convert');

Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'create', 'store']);
Route::post('purchase-orders/{purchaseOrder}/sync', [PurchaseOrderController::class, 'syncToAP'])
    ->name('purchase-orders.sync');

Route::post('approvals/process', [ApprovalController::class, 'process'])
    ->name('approvals.process');