<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable\Invoice as ArInvoice;
use App\Models\AccountPayable\Invoice as ApInvoice;
use App\Models\GeneralLedger\Entry;
use App\Models\GeneralLedger\Account;
use App\Models\FixedAssets\FixedAsset;
use App\Services\FinancialReportService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(protected FinancialReportService $reports)
    {
    }

    /**
     * GET /dashboard  -> the main "Welcome Back" dashboard
     */
    public function index()
    {
        $adminFirstName = optional(Auth::user())->first_name ?? 'Admin';

        // ================= ACCOUNT PAYABLE (real) =================
        // Invoice uses `status` and `total_amount` (not `payment_status` /
        // `amount` — those don't exist on this model, which is why this
        // query was silently failing and always falling back to null).
        //
        // Outstanding AP = total_amount minus whatever's already ACTUALLY
        // been paid per invoice. Only payments with status 'paid' count —
        // a 'scheduled' payment already has a row in ap_payments with the
        // full amount, but the money hasn't moved yet, so it must not be
        // subtracted (that mismatch is why this card didn't match the AP
        // module's own Total AP figure).
        try {
            $accountPayableRaw = ApInvoice::where('status', '!=', 'paid')
                ->withSum(['payments' => fn ($q) => $q->where('status', 'paid')], 'amount')
                ->get()
                ->sum(fn ($invoice) => $invoice->total_amount - ($invoice->payments_sum_amount ?? 0));

            $accountPayable = '₱' . number_format($accountPayableRaw, 2);
        } catch (\Throwable $e) {
            report($e);
            $accountPayable = null;
        }

        // =================Account Receivable======================
        
        try {
         $accountReceivableRaw = ArInvoice::sum('balance');
          $accountReceivable = '₱' . number_format($accountReceivableRaw, 2);
         } catch (\Throwable $e) {
          report($e);
          $accountReceivable = null;
         }

        // ================= GENERAL LEDGER =================
        try {
            $ledgerEntriesCount = Entry::whereMonth('entry_date', now()->month)
                ->whereYear('entry_date', now()->year)
                ->count();
            $ledgerEntries = number_format($ledgerEntriesCount);
        } catch (\Throwable $e) {
            report($e);
            $ledgerEntries = null;
        }

        // ================= CASH ON HAND (real, from General Ledger) =================
        // Cash on Hand = Debit - Credit on all entry lines posted against
        // the "Cash on Hand" GL account (account_code 1000 — same account
        // used everywhere else in GeneralLedgerService::CASH_ACCOUNT_CODE),
        // so this always reflects the actual ledger balance instead of a
        // duplicate figure sourced from headerStats().
        try {
            $cashAccount = Account::where('account_code', '1000')->first();

            if ($cashAccount) {
                $cashDebit = $cashAccount->entryLines()->sum('debit');
                $cashCredit = $cashAccount->entryLines()->sum('credit');
                $cashOnHandRaw = $cashDebit - $cashCredit;
                $cashOnHand = '₱' . number_format($cashOnHandRaw, 2);
            } else {
                $cashOnHand = null;
            }
        } catch (\Throwable $e) {
            report($e);
            $cashOnHand = null;
        }

        // ================= TOP STAT CARDS (via FinancialReportService) =================
        // Reusing the SAME service the Financial Reports pages already use
        // (FinancialReportsController -> $this->reports->headerStats($year)),
        // so these numbers are guaranteed consistent with what's shown there
        // instead of being a second, independently-computed version.
        try {
            $headerStats = $this->reports->headerStats(now()->year);

            $totalAssetsRaw = $this->pluck($headerStats, ['totalAssets', 'total_assets']);
            $netIncomeRaw = $this->pluck($headerStats, ['netIncome', 'net_income', 'netProfit', 'net_profit']);

            $totalAssets = $totalAssetsRaw !== null ? '₱' . number_format($totalAssetsRaw, 2) : null;
            $netProfit = $netIncomeRaw !== null ? '₱' . number_format($netIncomeRaw, 2) : null;

            // Compliance Score comes straight from headerStats(), the same
            // source the Financial Reports header card uses
            // (FinancialReportService::headerStats() -> 'complianceScore' /
            // 'complianceLabel', derived from complianceDonut()'s
            // fin_audits data), so it always matches what's shown there.
            $complianceScoreRaw = $this->pluck($headerStats, ['complianceScore', 'compliance_score']);
            $complianceLabelRaw = $this->pluck($headerStats, ['complianceLabel', 'compliance_label']);

            $complianceScore = $complianceScoreRaw !== null
                ? $complianceScoreRaw . '%' : null;
        } catch (\Throwable $e) {
            report($e);
            $totalAssets = null;
            $netProfit = null;
            $complianceScore = null;
        }
        // ================= FIXED ASSETS =================\
        try{
            $fixedAssetsRaw = FixedAsset::sum('book_value');
            $fixedAssets = '₱' . number_format($fixedAssetsRaw, 2);
        } catch (\Throwable $e) {
            report($e);
            $fixedAssets = null;
        }
        

        // ================= TODO: remaining =================
        $budgetEntries = null;

        return view('dashboard.dashboard', compact(
            'adminFirstName',
            'accountPayable',
            'ledgerEntries', 'accountReceivable', 'complianceScore', 'fixedAssets', 'budgetEntries',
            'totalAssets', 'netProfit', 'cashOnHand'
        ));
    }

    /**
     * Try several possible key names (array or object) against a data
     * structure and return the first match found, or null if none exist.
     * Used because headerStats()'s exact key names aren't confirmed yet.
     */
    protected function pluck(mixed $data, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (is_array($data) && array_key_exists($key, $data)) {
                return $data[$key];
            }
            if (is_object($data) && isset($data->{$key})) {
                return $data->{$key};
            }
        }

        return null;
    }
}