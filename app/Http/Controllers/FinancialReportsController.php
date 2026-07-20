<?php

namespace App\Http\Controllers;

use App\Models\FinAudit;
use App\Models\FinTaxCalendar;
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

        return response()->json([
            'cashflowMonthly' => $this->reports->cashFlowStatement($year),
            'taxSummary' => $this->reports->taxSummary($year),
            'taxCalculation' => $this->reports->taxCalculation($year),
        ]);
    }

    /**
     * Display the Cash Flow & Tax page.
     */
    public function cashflowTax(Request $request)
    {
        $years = $this->reports->availableYears();
        $year = (int) $request->query('year', $years[0] ?? now()->year);

        return view('financial-reports.cashflow-tax', [
            'years' => $years,
            'selectedYear' => $year,
            'headerStats' => $this->reports->headerStats($year),
            'cashflowMonthly' => $this->reports->cashFlowStatement($year),
            'taxSummary' => $this->reports->taxSummary($year),
            'taxCalculation' => $this->reports->taxCalculation($year),
            'taxCalendar' => $this->reports->taxCalendar(),
        ]);
    }

    /**
     * Display the Budget vs Actual page.
     */
    public function budgetActual(Request $request)
    {
        $years = $this->reports->availableYears();
        $year = (int) $request->query('year', $years[0] ?? now()->year);

        return view('financial-reports.budget-actual', [
            'years' => $years,
            'selectedYear' => $year,
            'headerStats' => $this->reports->headerStats($year),
        ]);
    }

    /**
     * Store a new audit scheduled from the header's "Add Audit" modal.
     *
     * Returns the audit in the SAME shape as FinancialReportService::audits(),
     * so the front end (overview.blade.php) can push it straight into its
     * in-memory AUDITS / AUDITS_CACHE arrays without a page reload or a
     * second round-trip to the server.
     */
    public function storeAudit(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:Internal,External,Regulatory,Financial'],
            'priority'    => ['nullable', 'in:low,medium,high,critical'],
            'date'        => ['required', 'date'],
            'recurrence'  => ['nullable', 'in:none,monthly,quarterly,annually'],
            'assigned_to' => ['nullable', 'string', 'max:255'],
            'checklist'   => ['nullable', 'array'],
            'checklist.*' => ['string', 'max:255'],
            'notify'      => ['nullable', 'boolean'],
            'notes'       => ['nullable', 'string'],
        ]);

        $scheduledDate = Carbon::parse($validated['date']);

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
        ], 201);
    }

    /**
     * Update an existing audit from the Overview page's "Edit Information"
     * modal. Returns the audit in the same shape as storeAudit()/audits()
     * so the front end can merge it straight into AUDITS / AUDITS_CACHE.
     */
    public function updateAudit(Request $request, FinAudit $audit)
    {
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
        ]);
    }

    /**
     * Delete an audit from the Overview page's Audit Detail modal.
     */
    public function destroyAudit(FinAudit $audit)
    {
        $id = $audit->id;
        $year = $audit->audit_year;
        $audit->delete();

        return response()->json(['deleted' => true, 'id' => $id, 'year' => $year]);
    }

    /**
     * Store a new tax calendar filing from the Cash Flow & Tax page's
     * "Add Filing" modal. Returns the item in the same shape the front
     * end already expects ({id, label, amount, date, status}) so it can
     * be pushed straight into TAX_CALENDAR_ITEMS.
     */
    public function storeTaxCalendarItem(Request $request)
    {
        $validated = $request->validate([
            'label'  => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date'   => ['required', 'date'],
            'status' => ['required', 'in:Upcoming,Filed,Overdue'],
        ]);

        $item = FinTaxCalendar::create([
            'label'    => $validated['label'],
            'due_date' => $validated['date'],
            'amount'   => $validated['amount'],
            'status'   => $validated['status'],
        ]);

        return response()->json(['item' => $this->formatTaxCalendarItem($item)], 201);
    }

    /**
     * Update an existing tax calendar filing from the "Edit Filing" modal.
     */
    public function updateTaxCalendarItem(Request $request, FinTaxCalendar $taxCalendar)
    {
        $validated = $request->validate([
            'label'  => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date'   => ['required', 'date'],
            'status' => ['required', 'in:Upcoming,Filed,Overdue'],
        ]);

        $taxCalendar->update([
            'label'    => $validated['label'],
            'due_date' => $validated['date'],
            'amount'   => $validated['amount'],
            'status'   => $validated['status'],
        ]);

        return response()->json(['item' => $this->formatTaxCalendarItem($taxCalendar)]);
    }

    /**
     * Delete a tax calendar filing from the Cash Flow & Tax page.
     */
    public function destroyTaxCalendarItem(FinTaxCalendar $taxCalendar)
    {
        $id = $taxCalendar->id;
        $taxCalendar->delete();

        return response()->json(['deleted' => true, 'id' => $id]);
    }

    /**
     * Shape a FinTaxCalendar row the way the Cash Flow & Tax page's JS
     * (TAX_CALENDAR_ITEMS) expects it.
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