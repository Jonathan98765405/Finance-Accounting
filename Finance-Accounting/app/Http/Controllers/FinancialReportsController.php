<?php

namespace App\Http\Controllers;

use App\Models\FinAudit;
use App\Models\FinTaxCalendar;
use App\Models\Role;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinancialReportsController extends Controller
{
    protected const MONTH_NAMES = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];

    public function __construct(protected FinancialReportService $reports)
    {
    }

    /**
     * Display the Financial Reports overview page.
     */
    public function overview(Request $request)
    {
        $years = $this->reports->availableYears();
        $year = (int) $request->query('year', $years[0] ?? now()->year);

        return view('financial-reports.overview', [
            'years' => $years,
            'selectedYear' => $year,
            'headerStats' => $this->reports->headerStats($year),
            'revenueSeries' => $this->reports->monthlyRevenue($year),
            'profitSeries' => $this->reports->monthlyProfit($year),
            'complianceDonut' => $this->reports->complianceDonut($year),
            'monthlyReports' => $this->reports->monthlyReports($year),
            'audits' => $this->reports->audits($year),
            'activities' => $this->reports->recentComplianceActivities(),
        ]);
    }

    /**
     * Display the Income & Balance page.
     */
    public function incomeBalance(Request $request)
    {
        $years = $this->reports->availableYears();
        $year = (int) $request->query('year', $years[0] ?? now()->year);

        return view('financial-reports.income-balance', [
            'years' => $years,
            'selectedYear' => $year,
            'headerStats' => $this->reports->headerStats($year),
            'incomeStatement' => $this->reports->incomeStatement($year),
            'balanceSheet' => $this->reports->balanceSheet($year),
        ]);
    }

    /**
     * JSON endpoint used by the Income & Balance page when the user
     * switches the year selector (avoids a full page reload).
     */
    public function incomeBalanceData(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        return response()->json([
            'incomeStatement' => $this->reports->incomeStatement($year),
            'balanceSheet' => $this->reports->balanceSheet($year),
        ]);
    }

    /**
     * JSON endpoint used by the Overview page when the user switches years.
     */
    public function overviewData(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        return response()->json([
            'revenueSeries' => $this->reports->monthlyRevenue($year),
            'profitSeries' => $this->reports->monthlyProfit($year),
            'complianceDonut' => $this->reports->complianceDonut($year),
            'monthlyReports' => $this->reports->monthlyReports($year),
            'audits' => $this->reports->audits($year),
        ]);
    }

    /**
     * JSON endpoint used by the Cash Flow & Tax page when the user switches years.
     */
    public function cashflowTaxData(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        $taxYear = (int) $request->query('tax_year', $year);
        $taxMonth = $this->parseTaxMonth($request->query('tax_month'));

        return response()->json([
            'cashflowMonthly' => $this->reports->cashFlowStatement($year),
            'taxSummary' => $this->reports->taxSummary($taxYear, $taxMonth),
            'taxCalculation' => $this->reports->taxCalculation($taxYear, $taxMonth),
        ]);
    }

    /**
     * Display the Cash Flow & Tax page.
     */
    public function cashflowTax(Request $request)
    {
        $years = $this->reports->availableYears();
        $year = (int) $request->query('year', $years[0] ?? now()->year);
        $taxYear = (int) $request->query('tax_year', $year);
        $taxMonth = $this->parseTaxMonth($request->query('tax_month', now()->month));

        return view('financial-reports.cashflow-tax', [
            'years' => $years,
            'selectedYear' => $year,
            'headerStats' => $this->reports->headerStats($year),
            'cashflowMonthly' => $this->reports->cashFlowStatement($year),
            'taxSummary' => $this->reports->taxSummary($taxYear, $taxMonth),
            'taxCalculation' => $this->reports->taxCalculation($taxYear, $taxMonth),
            'taxCalcYear' => $taxYear,
            'taxCalcMonth' => $taxMonth,
            'taxCalendar' => $this->reports->taxCalendar(),
        ]);
    }

    /**
     * Normalize the Tax Calculation month selector: null/empty/'all' means
     * "full year total", otherwise it's clamped to a valid month number.
     */
    protected function parseTaxMonth(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === 'all') {
            return null;
        }

        $month = (int) $value;

        return ($month >= 1 && $month <= 12) ? $month : null;
    }

    /**
     * Build a "Recent Compliance Activities" entry in the same shape the
     * Overview page's JS expects, so every audit/tax action can hand one
     * back and the front-end can prepend it to the feed live instead of
     * only ever showing whatever was there on the last page load.
     */
    protected function buildActivity(string $icon, string $iconColor, string $title, string $type, ?string $notes, string $color = 'text-slate-400'): array
    {
        return [
            'title'     => $title,
            'type'      => $type,
            'notes'     => $notes ?? '',
            'color'     => $color,
            'when'      => 'Just now',
        ];

        
    }

    /**
     * Store a new audit scheduled from the header's "Add Audit" modal.
     */
    public function storeAudit(Request $request)
    {
        if (!Role::activeRoleCanManageFinancialReports()) {
            return response()->json(['message' => 'Access Denied: You don\'t have permission for this action.'], 403);
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:Internal,External,Regulatory,Financial'],
            'priority'    => ['nullable', 'in:low,medium,high,critical'],
            'date'        => ['nullable', 'date', 'required_without_all:year,month'],
            'year'        => ['nullable', 'integer', 'digits:4', 'required_without:date'],
            'month'       => ['nullable', 'integer', 'min:1', 'max:12', 'required_without:date'],
            'recurrence'  => ['nullable', 'in:none,monthly,quarterly,annually'],
            'assigned_to' => ['nullable', 'string', 'max:255'],
            'checklist'   => ['nullable', 'array'],
            'checklist.*' => ['string', 'max:255'],
            'notify'      => ['nullable', 'boolean'],
            'notes'       => ['nullable', 'string'],
        ]);

        // Quick-add flow (e.g. "Add Audit" from a Monthly Report row that has
        // no audit yet) sends only year + month; fall back to the 1st of that
        // month so we always end up with a concrete scheduled_date.
        $scheduledDate = isset($validated['date'])
            ? Carbon::parse($validated['date'])
            : Carbon::create((int) $validated['year'], (int) $validated['month'], 1);

        $audit = FinAudit::create([
            'name'           => $validated['name'],
            'audit_year'     => $scheduledDate->year,
            'audit_month'    => $scheduledDate->month,
            'audit_type'     => $validated['type'],
            'priority'       => $validated['priority'] ?? 'medium',
            'scheduled_date' => $scheduledDate,
            'recurrence'     => $validated['recurrence'] ?? 'none',
            'auditor'        => $validated['assigned_to'] ?: 'Unassigned',
            'status'         => 'Pending',
            'findings'       => $validated['notes'] ?? null,
            'checklist'      => $validated['checklist'] ?? [],
            'notify'         => $validated['notify'] ?? false,
        ]);

        return response()->json([
            'audit' => [
                'id' => $audit->id,
                'year' => $audit->audit_year,
                'month' => self::MONTH_NAMES[$audit->audit_month] ?? null,
                'date' => $scheduledDate->format('M j, Y'),
                'auditType' => $audit->audit_type,
                'auditor' => $audit->auditor,
                'status' => $audit->status,
                'dateCompleted' => $audit->date_completed,
                'findings' => $audit->findings,
            ],
            'activity' => $this->buildActivity(
                'file-plus',
                'text-navy-600',
                "{$audit->audit_type} audit scheduled for " . (self::MONTH_NAMES[$audit->audit_month] ?? '') . " {$audit->audit_year}",
                'Audit',
                "Assigned to {$audit->auditor}.",
                'text-brand-orange'
            ),
        ], 201);
    }

    /**
     * Update an existing audit from the Overview page's "Edit Information" modal.
     */
    public function updateAudit(Request $request, FinAudit $audit)
    {
        if (!Role::activeRoleCanManageFinancialReports()) {
            return response()->json(['message' => 'Access Denied: You don\'t have permission for this action.'], 403);
        }

        $validated = $request->validate([
            'auditType'     => ['required', 'in:Internal,External,Regulatory,Financial'],
            'auditor'       => ['required', 'string', 'max:255'],
            'status'        => ['required', 'in:Complaint,Pending,Failed'],
            'dateCompleted' => ['nullable', 'date'],
            'findings'      => ['nullable', 'string'],
        ]);

        $audit->update([
            'audit_type'     => $validated['auditType'],
            'auditor'        => $validated['auditor'],
            'status'         => $validated['status'],
            'date_completed' => $validated['dateCompleted'] ?? null,
            'findings'       => $validated['findings'] ?? null,
        ]);

        $displayDate = $audit->scheduled_date ?? $audit->date_completed;

        $statusColor = match ($audit->status) {
            'Complaint' => 'text-brand-green',
            'Failed' => 'text-brand-red',
            default => 'text-brand-orange',
        };

        return response()->json([
            'audit' => [
                'id' => $audit->id,
                'year' => $audit->audit_year,
                'month' => self::MONTH_NAMES[$audit->audit_month] ?? null,
                'date' => $displayDate ? Carbon::parse($displayDate)->format('M j, Y') : null,
                'auditType' => $audit->audit_type,
                'auditor' => $audit->auditor,
                'status' => $audit->status,
                'dateCompleted' => $audit->date_completed,
                'findings' => $audit->findings,
            ],
            'activity' => $this->buildActivity(
                'check-circle',
                $statusColor,
                "{$audit->audit_type} audit for " . (self::MONTH_NAMES[$audit->audit_month] ?? '') . " {$audit->audit_year} marked as {$audit->status}",
                'Audit',
                $audit->findings,
                $statusColor
            ),
        ]);
    }

    /**
     * Delete an audit from the Overview page's Audit Detail modal.
     */
    public function destroyAudit(FinAudit $audit)
    {
        if (!Role::activeRoleCanManageFinancialReports()) {
            return response()->json(['message' => 'Access Denied: You don\'t have permission for this action.'], 403);
        }

        $id = $audit->id;
        $year = $audit->audit_year;
        $month = self::MONTH_NAMES[$audit->audit_month] ?? null;
        $auditType = $audit->audit_type;
        $audit->delete();

        return response()->json([
            'deleted' => true,
            'id' => $id,
            'year' => $year,
            'activity' => $this->buildActivity(
                'trash-2',
                'text-brand-red',
                "{$auditType} audit for {$month} {$year} removed",
                'Audit',
                null,
                'text-slate-400'
            ),
        ]);
    }

    /**
     * Store a new tax calendar filing from the Cash Flow & Tax page's "Add Filing" modal.
     */
    public function storeTaxCalendarItem(Request $request)
    {
        if (!Role::activeRoleCanManageFinancialReports()) {
            return response()->json(['message' => 'Access Denied: You don\'t have permission for this action.'], 403);
        }

        $validated = $request->validate([
            'label'  => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date'   => ['required', 'date'],
            'status' => ['required', 'in:Upcoming,Filed,Overdue'],
        ]);

        $item = FinTaxCalendar::create([
            'label'     => $validated['label'],
            'due_date'  => $validated['date'],
            'tax_year'  => Carbon::parse($validated['date'])->year,
            'tax_month' => Carbon::parse($validated['date'])->month,
            'amount'    => $validated['amount'],
            'status'    => $validated['status'],
        ]);

        return response()->json([
            'item' => $this->formatTaxCalendarItem($item),
            'activity' => $this->buildActivity(
                'calendar-plus',
                'text-navy-600',
                "Tax filing \"{$item->label}\" added to calendar",
                'Tax Filing',
                'Due ' . Carbon::parse($item->due_date)->format('M j, Y') . '.',
                'text-brand-orange'
            ),
        ], 201);
    }

    /**
     * Update an existing tax calendar filing from the "Edit Filing" modal.
     */
    public function updateTaxCalendarItem(Request $request, FinTaxCalendar $taxCalendar)
    {
        if (!Role::activeRoleCanManageFinancialReports()) {
            return response()->json(['message' => 'Access Denied: You don\'t have permission for this action.'], 403);
        }

        $validated = $request->validate([
            'label'  => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date'   => ['required', 'date'],
            'status' => ['required', 'in:Upcoming,Filed,Overdue'],
        ]);

        $taxCalendar->update([
            'label'     => $validated['label'],
            'due_date'  => $validated['date'],
            'tax_year'  => Carbon::parse($validated['date'])->year,
            'tax_month' => Carbon::parse($validated['date'])->month,
            'amount'    => $validated['amount'],
            'status'    => $validated['status'],
        ]);

        $statusColor = match ($taxCalendar->status) {
            'Filed' => 'text-brand-green',
            'Overdue' => 'text-brand-red',
            default => 'text-brand-orange',
        };

        return response()->json([
            'item' => $this->formatTaxCalendarItem($taxCalendar),
            'activity' => $this->buildActivity(
                'file-check',
                $statusColor,
                "Tax filing \"{$taxCalendar->label}\" marked as {$taxCalendar->status}",
                'Tax Filing',
                null,
                $statusColor
            ),
        ]);
    }

    /**
     * Delete a tax calendar filing from the Cash Flow & Tax page.
     */
    public function destroyTaxCalendarItem(FinTaxCalendar $taxCalendar)
    {
        if (!Role::activeRoleCanManageFinancialReports()) {
            return response()->json(['message' => 'Access Denied: You don\'t have permission for this action.'], 403);
        }

        $id = $taxCalendar->id;
        $label = $taxCalendar->label;
        $taxCalendar->delete();

        return response()->json([
            'deleted' => true,
            'id' => $id,
            'activity' => $this->buildActivity(
                'trash-2',
                'text-brand-red',
                "Tax filing \"{$label}\" removed from calendar",
                'Tax Filing',
                null,
                'text-slate-400'
            ),
        ]);
    }

    /**
     * Shape a FinTaxCalendar row.
     */
    protected function formatTaxCalendarItem(FinTaxCalendar $item): array
    {
        return [
            'id'     => $item->id,
            'label'  => $item->label,
            'amount' => (float) $item->amount,
            'date'   => $item->due_date instanceof Carbon
                ? $item->due_date->format('Y-m-d')
                : Carbon::parse($item->due_date)->format('Y-m-d'),
            'status' => $item->status,
        ];
    }
}
