<?php

namespace App\Http\Controllers;

use App\Models\AccountPayable\Invoice as ApInvoice;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * GET /dashboard  -> the main "Welcome Back" dashboard
     */
    public function index()
    {
        // FIX: Auth::user() can be null (e.g. not logged in, or the guard
        // isn't set up the way this route expects). Calling ->first_name on
        // null is a fatal error, which is the most likely cause of the white
        // screen. optional() makes this null-safe.
        $adminFirstName = optional(Auth::user())->first_name ?? 'Admin';

        // ================= ACCOUNT PAYABLE (real) =================
        // Wrapped in a try/catch so that if this table/model doesn't exist
        // yet, or something else is off, it won't fatal the whole page —
        // it'll just fall back to the Blade file's hardcoded placeholder.
        try {
            $accountPayableRaw = ApInvoice::whereNotIn('status', ['paid'])->sum('total_amount');
            $accountPayable = '₱' . number_format($accountPayableRaw, 2);
        } catch (\Throwable $e) {
            report($e); // still logs the real error to storage/logs/laravel.log
            $accountPayable = null; // Blade falls back to its placeholder
        }

        // ================= TODO: remaining modules =================
        // Left as null on purpose — Blade's `?? 'placeholder'` fallback
        // handles these until each is wired to a real model.
        $ledgerEntries = null;
        $accountReceivable = null;
        $complianceScore = null;
        $fixedAssets = null;
        $budgetEntries = null;
        $totalAssets = null;
        $netProfit = null;
        $cashOnHand = null;
        $openTasks = null;

        return view('dashboard.dashboard', compact(
            'adminFirstName',
            'accountPayable',
            'ledgerEntries', 'accountReceivable', 'complianceScore', 'fixedAssets', 'budgetEntries',
            'totalAssets', 'netProfit', 'cashOnHand', 'openTasks'
        ));
    }
}