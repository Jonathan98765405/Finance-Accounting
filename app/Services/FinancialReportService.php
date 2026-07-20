<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Builds every number shown on the Financial Reports pages
 * (Overview / Income & Balance / Cash Flow & Tax) directly from:
 *   - gl_accounts, gl_entries, gl_entry_lines   (General Ledger submodule)
 *   - fin_audits, fin_compliance_activities     (Audit / Compliance submodule)
 *   - fin_tax_filings, fin_tax_calendar         (Tax submodule)
 *
 * No figures are hardcoded here — everything is a query result.
 */
class FinancialReportService
{
    protected const CASH_ACCOUNT_CODE = '1000';
    protected const FIXED_ASSET_ACCOUNT_CODE = '1500';

    protected const MONTH_NAMES = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];

    protected const MONTH_ABBR = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
        7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec',
    ];

    /**
     * All years that have posted GL activity, tax filings, or audits —
     * used to populate every "year" <select> instead of a hardcoded 2026.
     */
    public function availableYears(): array
    {
        $glYears = DB::table('gl_entries')
            ->where('status', 'Posted')
            ->selectRaw('DISTINCT YEAR(entry_date) as y')
            ->pluck('y');

        $taxYears = DB::table('fin_tax_filings')->selectRaw('DISTINCT tax_year as y')->pluck('y');
        $auditYears = DB::table('fin_audits')->selectRaw('DISTINCT audit_year as y')->pluck('y');

        $years = $glYears->merge($taxYears)->merge($auditYears)
            ->map(fn ($y) => (int) $y)
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return $years ?: [now()->year];
    }

    /* =========================================================
     * INCOME STATEMENT
     * ========================================================= */

    public function incomeStatement(int $year): array
    {
        $rows = $this->postedLines($year)
            ->whereIn('gl_accounts.account_type', ['Revenue', 'Expense'])
            ->select(
                'gl_accounts.account_type',
                'gl_accounts.account_name',
                DB::raw('SUM(gl_entry_lines.debit) as total_debit'),
                DB::raw('SUM(gl_entry_lines.credit) as total_credit')
            )
            ->groupBy('gl_accounts.account_type', 'gl_accounts.account_name')
            ->get();

        $revenueLines = [];
        $expenseLines = [];
        $totalRevenue = 0.0;
        $totalExpense = 0.0;

        foreach ($rows as $row) {
            if ($row->account_type === 'Revenue') {
                $amount = (float) $row->total_credit - (float) $row->total_debit;
                $revenueLines[] = [$row->account_name, round($amount, 2)];
                $totalRevenue += $amount;
            } else {
                $amount = (float) $row->total_debit - (float) $row->total_credit;
                $expenseLines[] = [$row->account_name, -round($amount, 2)];
                $totalExpense += $amount;
            }
        }

        $netIncome = $totalRevenue - $totalExpense;

        return [
            'year' => $year,
            'groups' => [
                [
                    'label' => 'REVENUE',
                    'amount' => round($totalRevenue, 2),
                    'lines' => $revenueLines,
                ],
                [
                    'label' => 'Operating Expenses',
                    'amount' => -round($totalExpense, 2),
                    'lines' => $expenseLines,
                ],
            ],
            'subtotals' => [
                ['Operating Income', round($netIncome, 2)],
            ],
            'netIncome' => round($netIncome, 2),
            'totalRevenue' => round($totalRevenue, 2),
            'totalExpense' => round($totalExpense, 2),
        ];
    }

    /**
     * Monthly revenue series for the Overview revenue chart, Jan..Dec.
     */
    public function monthlyRevenue(int $year): array
    {
        return $this->monthlySeries($year, 'Revenue');
    }

    /**
     * Monthly net profit series (revenue - expense) for the Overview profit chart.
     */
    public function monthlyProfit(int $year): array
    {
        $revenue = $this->monthlySeries($year, 'Revenue');
        $expense = $this->monthlySeries($year, 'Expense');

        return array_map(fn ($r, $e) => round($r - $e, 2), $revenue, $expense);
    }

    protected function monthlySeries(int $year, string $accountType): array
    {
        $rows = $this->postedLines($year)
            ->where('gl_accounts.account_type', $accountType)
            ->select(
                DB::raw('MONTH(gl_entries.entry_date) as m'),
                DB::raw('SUM(gl_entry_lines.debit) as total_debit'),
                DB::raw('SUM(gl_entry_lines.credit) as total_credit')
            )
            ->groupBy('m')
            ->get()
            ->keyBy('m');

        $series = [];
        for ($m = 1; $m <= 12; $m++) {
            $row = $rows->get($m);
            if (! $row) {
                $series[] = 0.0;
                continue;
            }
            $series[] = $accountType === 'Revenue'
                ? round((float) $row->total_credit - (float) $row->total_debit, 2)
                : round((float) $row->total_debit - (float) $row->total_credit, 2);
        }

        return $series;
    }

    /* =========================================================
     * BALANCE SHEET (cumulative as of Dec 31 of $year)
     * ========================================================= */

    public function balanceSheet(int $year): array
    {
        $asOf = Carbon::create($year, 12, 31)->endOfDay();

        $rows = DB::table('gl_entry_lines')
            ->join('gl_entries', 'gl_entries.id', '=', 'gl_entry_lines.gl_entry_id')
            ->join('gl_accounts', 'gl_accounts.id', '=', 'gl_entry_lines.gl_account_id')
            ->where('gl_entries.status', 'Posted')
            ->where('gl_entries.entry_date', '<=', $asOf->toDateString())
            ->whereIn('gl_accounts.account_type', ['Asset', 'Liability', 'Equity'])
            ->select(
                'gl_accounts.account_type',
                'gl_accounts.account_name',
                DB::raw('SUM(gl_entry_lines.debit) as total_debit'),
                DB::raw('SUM(gl_entry_lines.credit) as total_credit')
            )
            ->groupBy('gl_accounts.account_type', 'gl_accounts.account_name')
            ->get();

        $assets = [];
        $liabilities = [];
        $equity = [];
        $totalAssets = 0.0;
        $totalLiabilities = 0.0;
        $totalEquity = 0.0;

        foreach ($rows as $row) {
            $debit = (float) $row->total_debit;
            $credit = (float) $row->total_credit;

            if ($row->account_type === 'Asset') {
                $balance = $debit - $credit;
                $assets[] = [$row->account_name, round($balance, 2)];
                $totalAssets += $balance;
            } elseif ($row->account_type === 'Liability') {
                $balance = $credit - $debit;
                $liabilities[] = [$row->account_name, round($balance, 2)];
                $totalLiabilities += $balance;
            } else { // Equity
                $balance = $credit - $debit;
                $equity[] = [$row->account_name, round($balance, 2)];
                $totalEquity += $balance;
            }
        }

        // Retained earnings = cumulative net income (Revenue - Expense) to date,
        // that hasn't been explicitly closed to an equity account. This keeps
        // Assets = Liabilities + Equity without requiring manual closing entries.
        $cumulativeIncome = DB::table('gl_entry_lines')
            ->join('gl_entries', 'gl_entries.id', '=', 'gl_entry_lines.gl_entry_id')
            ->join('gl_accounts', 'gl_accounts.id', '=', 'gl_entry_lines.gl_account_id')
            ->where('gl_entries.status', 'Posted')
            ->where('gl_entries.entry_date', '<=', $asOf->toDateString())
            ->whereIn('gl_accounts.account_type', ['Revenue', 'Expense'])
            ->select(
                'gl_accounts.account_type',
                DB::raw('SUM(gl_entry_lines.debit) as total_debit'),
                DB::raw('SUM(gl_entry_lines.credit) as total_credit')
            )
            ->groupBy('gl_accounts.account_type')
            ->get();

        $retainedEarnings = 0.0;
        foreach ($cumulativeIncome as $row) {
            $retainedEarnings += $row->account_type === 'Revenue'
                ? ((float) $row->total_credit - (float) $row->total_debit)
                : -((float) $row->total_debit - (float) $row->total_credit);
        }

        if (round($retainedEarnings, 2) !== 0.0) {
            $equity[] = ['Retained Earnings (Net Income to Date)', round($retainedEarnings, 2)];
            $totalEquity += $retainedEarnings;
        }

        return [
            'year' => $year,
            'assets' => ['title' => 'Total Assets', 'total' => round($totalAssets, 2), 'lines' => $assets],
            'liabilities' => ['title' => 'Total Liabilities', 'total' => round($totalLiabilities, 2), 'lines' => $liabilities],
            'equity' => ['title' => 'Total Equity', 'total' => round($totalEquity, 2), 'lines' => $equity],
        ];
    }

    /* =========================================================
     * CASH FLOW STATEMENT
     *
     * Classification rule (derived purely from the GL, no separate table):
     *   - the cash-account (1000) side of every posted entry is inspected
     *   - if the offsetting line touches an Equity account   -> Financing
     *   - if the offsetting line touches the Fixed Assets acct (1500) -> Investing
     *   - everything else (AR/AP/Revenue/Expense movements)  -> Operating
     * ========================================================= */

    public function cashFlowStatement(int $year): array
    {
        $cashAccountId = DB::table('gl_accounts')->where('account_code', self::CASH_ACCOUNT_CODE)->value('id');
        $fixedAssetAccountId = DB::table('gl_accounts')->where('account_code', self::FIXED_ASSET_ACCOUNT_CODE)->value('id');

        if (! $cashAccountId) {
            return $this->emptyCashFlowYear($year);
        }

        $cashLines = DB::table('gl_entry_lines as cash')
            ->join('gl_entries', 'gl_entries.id', '=', 'cash.gl_entry_id')
            ->where('cash.gl_account_id', $cashAccountId)
            ->where('gl_entries.status', 'Posted')
            ->whereYear('gl_entries.entry_date', $year)
            ->select('cash.gl_entry_id', 'cash.debit', 'cash.credit', 'gl_entries.entry_date')
            ->get();

        $monthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthly[$m] = ['operating' => 0.0, 'investing' => 0.0, 'financing' => 0.0];
        }

        foreach ($cashLines as $cashLine) {
            $netMovement = (float) $cashLine->debit - (float) $cashLine->credit;
            $month = (int) Carbon::parse($cashLine->entry_date)->format('n');

            $offsetTypes = DB::table('gl_entry_lines')
                ->join('gl_accounts', 'gl_accounts.id', '=', 'gl_entry_lines.gl_account_id')
                ->where('gl_entry_lines.gl_entry_id', $cashLine->gl_entry_id)
                ->where('gl_entry_lines.gl_account_id', '!=', $cashAccountId)
                ->pluck('gl_accounts.account_type', 'gl_entry_lines.gl_account_id');

            $isFinancing = $offsetTypes->contains('Equity');
            $isInvesting = $fixedAssetAccountId && $offsetTypes->keys()->contains($fixedAssetAccountId);

            if ($isFinancing) {
                $monthly[$month]['financing'] += $netMovement;
            } elseif ($isInvesting) {
                $monthly[$month]['investing'] += $netMovement;
            } else {
                $monthly[$month]['operating'] += $netMovement;
            }
        }

        $result = [];
        foreach ($monthly as $m => $totals) {
            $result[] = [
                'year' => $year,
                'month' => self::MONTH_NAMES[$m],
                'abbr' => self::MONTH_ABBR[$m],
                'operating' => round($totals['operating'], 2),
                'investing' => round($totals['investing'], 2),
                'financing' => round($totals['financing'], 2),
                'net' => round($totals['operating'] + $totals['investing'] + $totals['financing'], 2),
            ];
        }

        return $result;
    }

    protected function emptyCashFlowYear(int $year): array
    {
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[] = [
                'year' => $year, 'month' => self::MONTH_NAMES[$m], 'abbr' => self::MONTH_ABBR[$m],
                'operating' => 0.0, 'investing' => 0.0, 'financing' => 0.0, 'net' => 0.0,
            ];
        }

        return $result;
    }

    /* =========================================================
     * COMPLIANCE / AUDITS  (fin_audits, fin_compliance_activities)
     * ========================================================= */

    public function complianceDonut(int $year): array
    {
        $counts = DB::table('fin_audits')
            ->where('audit_year', $year)
            ->select('status', DB::raw('COUNT(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $complaint = (int) ($counts['Complaint'] ?? 0);
        $pending = (int) ($counts['Pending'] ?? 0);
        $failed = (int) ($counts['Failed'] ?? 0);
        $total = max($complaint + $pending + $failed, 1);

        return [
            'complaint' => round($complaint / $total * 100),
            'pending' => round($pending / $total * 100),
            'failed' => round($failed / $total * 100),
            'total' => $complaint + $pending + $failed,
        ];
    }

    public function audits(int $year): array
    {
        return DB::table('fin_audits')
            ->where('audit_year', $year)
            ->orderByRaw('COALESCE(scheduled_date, date_completed) DESC')
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'name' => $a->name,
                'year' => $a->audit_year,
                'month' => self::MONTH_NAMES[$a->audit_month] ?? null,
                // Exact date shown in Audit History: prefer the scheduled
                // date (set when the audit is created), fall back to
                // date_completed for older rows that predate that column.
                'date' => $a->scheduled_date
                    ? Carbon::parse($a->scheduled_date)->format('M j, Y')
                    : ($a->date_completed ? Carbon::parse($a->date_completed)->format('M j, Y') : null),
                'rawDate' => $a->scheduled_date ?? $a->date_completed,
                'auditType' => $a->audit_type,
                'priority' => $a->priority,
                'auditor' => $a->auditor,
                'status' => $a->status,
                'dateCompleted' => $a->date_completed,
                'findings' => $a->findings,
            ])
            ->all();
    }

    public function recentComplianceActivities(int $limit = 20): array
    {
        return DB::table('fin_compliance_activities')
            ->orderByDesc('activity_date')
            ->limit($limit)
            ->get()
            ->map(fn ($a) => [
                'title' => $a->title,
                'date' => $a->activity_date,
                'type' => $a->activity_type,
                'status' => $a->status,
                'notes' => $a->notes,
                'when' => Carbon::parse($a->activity_date)->diffForHumans(),
            ])
            ->all();
    }

    /**
     * Monthly reports table on Overview: revenue/expense/profit/margin per
     * month plus that month's audit result, all sourced live.
     */
    public function monthlyReports(int $year): array
    {
        $revenue = $this->monthlySeries($year, 'Revenue');
        $expense = $this->monthlySeries($year, 'Expense');

        $auditByMonth = DB::table('fin_audits')
            ->where('audit_year', $year)
            ->get()
            ->keyBy('audit_month');

        $reports = [];
        for ($m = 1; $m <= 12; $m++) {
            $rev = $revenue[$m - 1];
            $exp = $expense[$m - 1];
            $profit = $rev - $exp;
            $audit = $auditByMonth->get($m);

            $reports[] = [
                'year' => $year,
                'month' => self::MONTH_NAMES[$m],
                'abbr' => self::MONTH_ABBR[$m],
                'revenue' => round($rev, 2),
                'expenses' => round($exp, 2),
                'profit' => round($profit, 2),
                'margin' => $rev != 0 ? round($profit / $rev * 100, 1) . '%' : '0.0%',
                'compliance' => $audit->status ?? null,
                'auditType' => $audit->audit_type ?? null,
                'taxFiled' => ($audit->status ?? null) === 'Failed' ? 'No' : 'Yes',
                'notes' => $audit->findings ?? null,
            ];
        }

        return $reports;
    }

    /* =========================================================
     * TAX (fin_tax_filings, fin_tax_calendar)
     * ========================================================= */

    public function taxCalculation(int $year): array
    {
        return DB::table('fin_tax_filings')
            ->where('tax_year', $year)
            ->get()
            ->map(fn ($t) => [
                'type' => $t->tax_type,
                'rate' => $t->rate,
                'taxableAmount' => (float) $t->taxable_amount,
                'amountDue' => (float) $t->amount_due,
                'deadline' => Carbon::parse($t->deadline)->format('M j, Y'),
                'status' => $t->status,
            ])
            ->all();
    }

    public function taxSummary(int $year): array
    {
        $filings = DB::table('fin_tax_filings')->where('tax_year', $year)->get();

        return [
            'totalDue' => round((float) $filings->sum('amount_due'), 2),
            'filedYtd' => round((float) $filings->where('status', 'Filed')->sum('amount_due'), 2),
            'pendingFilings' => $filings->whereIn('status', ['Pending', 'Calculated'])->count(),
        ];
    }

    public function taxCalendar(): array
    {
        return DB::table('fin_tax_calendar')
            ->orderBy('due_date')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'label' => $t->label,
                'date' => $t->due_date,
                'amount' => (float) $t->amount,
                'status' => $t->status,
            ])
            ->all();
    }

    /* =========================================================
     * HEADER STAT CARDS (Total Revenue / Net Profit / Compliance
     * Score / Pending Reports) — used by financial-reports header
     * partial. Trends compare the two most recent months that
     * actually have posted GL activity within $year; if there
     * aren't two such months yet, the trend is simply omitted
     * rather than faked.
     * ========================================================= */

    public function headerStats(int $year): array
    {
        $revenue = $this->monthlySeries($year, 'Revenue');
        $profit = $this->monthlyProfit($year);

        $totalRevenue = array_sum($revenue);
        $totalProfit = array_sum($profit);

        [$currentIdx, $previousIdx] = $this->lastTwoActiveMonths($revenue);

        $revenueTrend = $this->percentChange($revenue, $currentIdx, $previousIdx);
        $profitTrend = $this->percentChange($profit, $currentIdx, $previousIdx);

        $compliance = $this->complianceDonut($year);
        $complianceScore = (int) $compliance['complaint'];

        $pendingAudits = DB::table('fin_audits')
            ->where('audit_year', $year)
            ->where('status', 'Pending')
            ->count();

        $pendingTax = DB::table('fin_tax_filings')
            ->where('tax_year', $year)
            ->whereIn('status', ['Pending', 'Calculated'])
            ->count();

        return [
            'year' => $year,
            'totalRevenue' => round($totalRevenue, 2),
            'totalRevenueTrend' => $revenueTrend,
            'netProfit' => round($totalProfit, 2),
            'netProfitTrend' => $profitTrend,
            'complianceScore' => $complianceScore,
            'complianceLabel' => $this->complianceLabel($complianceScore),
            'pendingReports' => $pendingAudits + $pendingTax,
        ];
    }

    /**
     * Given a monthly series (index 0 = Jan .. 11 = Dec), returns
     * [mostRecentActiveMonthIndex, priorActiveMonthIndex]. Either or
     * both can be null if there isn't enough posted activity yet.
     */
    protected function lastTwoActiveMonths(array $series): array
    {
        $active = [];
        foreach ($series as $i => $value) {
            if ((float) $value !== 0.0) {
                $active[] = $i;
            }
        }

        $count = count($active);
        if ($count >= 2) {
            return [$active[$count - 1], $active[$count - 2]];
        }
        if ($count === 1) {
            return [$active[0], null];
        }

        return [null, null];
    }

    protected function percentChange(array $series, ?int $currentIdx, ?int $previousIdx): ?float
    {
        if ($currentIdx === null || $previousIdx === null) {
            return null;
        }

        $current = (float) $series[$currentIdx];
        $previous = (float) $series[$previousIdx];

        if ($previous == 0.0) {
            return null;
        }

        return round((($current - $previous) / abs($previous)) * 100, 1);
    }

    protected function complianceLabel(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Excellent',
            $score >= 75 => 'Good',
            $score >= 50 => 'Needs Attention',
            default => 'At Risk',
        };
    }

    /* =========================================================
     * Shared query helper
     * ========================================================= */

    protected function postedLines(int $year)
    {
        return DB::table('gl_entry_lines')
            ->join('gl_entries', 'gl_entries.id', '=', 'gl_entry_lines.gl_entry_id')
            ->join('gl_accounts', 'gl_accounts.id', '=', 'gl_entry_lines.gl_account_id')
            ->where('gl_entries.status', 'Posted')
            ->whereYear('gl_entries.entry_date', $year);
    }
}