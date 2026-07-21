<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable\Invoice as ArInvoice;
use App\Models\AccountPayable\Invoice as ApInvoice;
use App\Models\GeneralLedger\Entry;
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
        try {
            $accountPayableRaw = ApInvoice::whereNotIn('status', ['paid'])->sum('total_amount');
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

        // ================= TOP STAT CARDS (via FinancialReportService) =================
        // Reusing the SAME service the Financial Reports pages already use
        // (FinancialReportsController -> $this->reports->headerStats($year)),
        // so these numbers are guaranteed consistent with what's shown there
        // instead of being a second, independently-computed version.
        try {
            $headerStats = $this->reports->headerStats(now()->year);

            $totalAssetsRaw = $this->pluck($headerStats, ['totalAssets', 'total_assets']);
            $netIncomeRaw = $this->pluck($headerStats, ['netIncome', 'net_income', 'netProfit', 'net_profit']);
            $cashOnHandRaw = $this->pluck($headerStats, ['cashOnHand', 'cash_on_hand', 'cash']);

            $totalAssets = $totalAssetsRaw !== null ? '₱' . number_format($totalAssetsRaw, 2) : null;
            $netProfit = $netIncomeRaw !== null ? '₱' . number_format($netIncomeRaw, 2) : null;
            $cashOnHand = $cashOnHandRaw !== null ? '₱' . number_format($cashOnHandRaw, 2) : null;

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
            $cashOnHand = null;
            $complianceScore = null;
        }

        // ================= TODO: remaining =================
        
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