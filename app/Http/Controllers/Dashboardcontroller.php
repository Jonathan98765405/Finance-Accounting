<?php

namespace App\Http\Controllers;
use App\Models\AccountReceivable\Invoice as ArInvoice;
use App\Models\AccountPayable\Invoice as ApInvoice;
use App\Models\GeneralLedger\Account;
use App\Models\GeneralLedger\Entry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * GET /dashboard  -> the main "Welcome Back" dashboard
     */
    public function index()
    {
        $adminFirstName = optional(Auth::user())->first_name ?? 'Admin';

        // ================= ACCOUNT PAYABLE (real) =================
        try {
            $accountPayableRaw = ApInvoice::whereNotIn('status', ['paid'])->sum('total_amount');
            $accountPayable = '₱' . number_format($accountPayableRaw, 2);
        } catch (\Throwable $e) {
            report($e);
            $accountPayable = null;
        }

        // ================= GENERAL LEDGER =================
        // Fixed: model is App\Models\GeneralLedger\Entry (not JournalEntry),
        // confirmed from GeneralLedgerController.
        try {
            $ledgerEntriesCount = Entry::whereMonth('entry_date', now()->month)
                ->whereYear('entry_date', now()->year)
                ->count();
            $ledgerEntries = number_format($ledgerEntriesCount);
        } catch (\Throwable $e) {
            report($e);
            $ledgerEntries = null;
        }

        // ================= TOP STAT CARDS =================
        // Reuses the exact same per-account debit/credit aggregation as
        // GeneralLedgerController::index() (via Account::entryLines()),
        // so these numbers will match the General Ledger page's own cards.
        // Net Profit here = Net Income = SUM(Revenue) - SUM(Expense),
        // ALL-TIME (same as the GL page's "Net Income" card — GL doesn't
        // filter by month there, so this doesn't either, to stay consistent).
        try {
            $accounts = Account::all();

            $totalAssetsRaw = 0;
            $cashOnHandRaw = 0;
            $totalRevenue = 0;
            $totalExpense = 0;

            foreach ($accounts as $account) {
                $debit = $account->entryLines()->sum('debit');
                $credit = $account->entryLines()->sum('credit');

                if ($account->account_type === 'Asset') {
                    $totalAssetsRaw += ($debit - $credit);
                }

                // "Cash on Hand" is an actual account name in this schema
                // (confirmed from the General Ledger screenshot), not a
                // guessed category — exact match first, falls back to a
                // loose match only if no exact-named account exists.
                if ($account->account_name === 'Cash on Hand') {
                    $cashOnHandRaw += ($debit - $credit);
                }

                if ($account->account_type === 'Revenue') {
                    $totalRevenue += ($credit - $debit);
                }

                if ($account->account_type === 'Expense') {
                    $totalExpense += ($debit - $credit);
                }
            }

            // Fallback if no account is named exactly "Cash on Hand"
            if ($cashOnHandRaw === 0 && ! $accounts->contains('account_name', 'Cash on Hand')) {
                foreach ($accounts as $account) {
                    if (stripos($account->account_name, 'cash') !== false) {
                        $cashOnHandRaw += ($account->entryLines()->sum('debit') - $account->entryLines()->sum('credit'));
                    }
                }
            }

            $totalAssets = '₱' . number_format($totalAssetsRaw, 2);
            $cashOnHand = '₱' . number_format($cashOnHandRaw, 2);
            $netProfit = '₱' . number_format($totalRevenue - $totalExpense, 2);
        } catch (\Throwable $e) {
            report($e);
            $totalAssets = null;
            $cashOnHand = null;
            $netProfit = null;
        }

        // ================= TODO: remaining modules =================
        // Left as null on purpose — Blade's `?? 'placeholder'` fallback
        // handles these until each is wired to a real model.
        try {
         $accountReceivableRaw = ArInvoice::sum('balance');
          $accountReceivable = '₱' . number_format($accountReceivableRaw, 2);
         } catch (\Throwable $e) {
          report($e);
          $accountReceivable = null;
         }
        $complianceScore = null;
        $fixedAssets = null;
        $budgetEntries = null;
        $openTasks = null;

        return view('dashboard.dashboard', compact(
            'adminFirstName',
            'accountPayable',
            'ledgerEntries', 'accountReceivable', 'complianceScore', 'fixedAssets', 'budgetEntries',
            'totalAssets', 'netProfit', 'cashOnHand', 'openTasks'
        ));
    }
}