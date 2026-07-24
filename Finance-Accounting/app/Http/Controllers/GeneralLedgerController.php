<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralLedger\Account;
use App\Models\GeneralLedger\Entry;
use App\Models\GeneralLedger\EntryLine;
use Illuminate\Support\Facades\DB;

class GeneralLedgerController extends Controller
{
    /**
     * Order entries by when they were actually recorded (created_at / id),
     * not by their entry_date — avoids timezone mismatches between
     * entry_date (business date on the invoice/payment) and "today"
     * as seen by the filter.
     */
    protected function applySortOrder($query, Request $request)
    {
        $direction = $request->input('sort', 'latest') === 'oldest' ? 'asc' : 'desc';

        return $query->orderBy('created_at', $direction)
                      ->orderBy('id', $direction);
    }

    public function index(Request $request)
    {
        $query = Entry::with(['lines.account']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('reference', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('account_id')) {
            $query->whereHas('lines', function ($q) use ($request) {
                $q->where('gl_account_id', $request->account_id);
            });
        }

        // Only the 5 most recent (recorded) entries are needed on the dashboard view.
        $entries = $this->applySortOrder($query, $request)
            ->take(3)
            ->get();

        $accounts = Account::all();

        // Calculate Trial Balance & Metrics
        $trialBalance = [];
        $totalAssets = 0;
        $totalLiabilities = 0;
        $totalEquity = 0;
        $totalRevenue = 0;
        $totalExpense = 0;

        foreach ($accounts as $account) {
            $debit = $account->entryLines()->sum('debit');
            $credit = $account->entryLines()->sum('credit');

            $trialBalance[] = [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'debit' => $debit,
                'credit' => $credit,
            ];

            // Dashboard Metrics Calculation
            $balance = 0;
            switch ($account->account_type) {
                case 'Asset':
                    $totalAssets += ($debit - $credit);
                    break;
                case 'Liability':
                    $totalLiabilities += ($credit - $debit);
                    break;
                case 'Equity':
                    $totalEquity += ($credit - $debit);
                    break;
                case 'Revenue':
                    $totalRevenue += ($credit - $debit);
                    break;
                case 'Expense':
                    $totalExpense += ($debit - $credit);
                    break;
            }
        }

        $netIncome = $totalRevenue - $totalExpense;

        return view('general-ledger.index', compact(
            'entries', 'accounts', 'trialBalance',
            'totalAssets', 'totalLiabilities', 'totalEquity', 'netIncome'
        ));
    }

    public function create()
    {
        $accounts = Account::where('status', 'Active')->get();
        return view('general-ledger.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        // Validation and logic would go here
        return redirect()->route('ledger.index')
            ->with('success', 'Journal entry saved successfully.');
    }

    public function show($id)
    {
        $entry = Entry::with('lines.account')->findOrFail($id);
        return view('general-ledger.show', compact('entry'));
    }

    public function edit($id)
    {
        $entry = Entry::findOrFail($id);
        return view('general-ledger.edit', compact('entry'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'reference' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        $entry = Entry::findOrFail($id);
        $entry->update($request->only('entry_date', 'reference', 'description'));

        return redirect()->route('ledger.index')
            ->with('success', 'Journal entry updated successfully.');
    }

    public function destroy($id)
    {
        $entry = Entry::findOrFail($id);
        $entry->delete();

        return redirect()->route('ledger.index')
            ->with('success', 'Journal entry deleted successfully.');
    }

    public function chartAccounts()
    {
        $accounts = Account::orderBy('account_code', 'asc')->get();
        return view('general-ledger.chart-accounts', compact('accounts'));
    }

    public function trialBalance()
    {
        $accounts = Account::all();
        $trialBalance = [];

        foreach ($accounts as $account) {
            $trialBalance[] = [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'debit' => $account->entryLines()->sum('debit'),
                'credit' => $account->entryLines()->sum('credit'),
            ];
        }

        return view('general-ledger.trial-balance', compact('trialBalance'));
    }

    /**
     * Full, paginated list of every journal entry (the "View All" page).
     * Supports the same status / search / account filters as the dashboard.
     */
    public function journalAll(Request $request)
    {
        $query = Entry::with(['lines.account']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('reference', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('account_id')) {
            $query->whereHas('lines', function ($q) use ($request) {
                $q->where('gl_account_id', $request->account_id);
            });
        }

        $entries = $this->applySortOrder($query, $request)
            ->paginate(15)
            ->withQueryString();

        $accounts = Account::all();

        return view('general-ledger.all-journal', compact('entries', 'accounts'));
    }
}