<?php

namespace App\Http\Controllers;


use App\Models\AccountReceivable\Payment;
use App\Models\AccountReceivable\InvoiceItem;
use App\Models\AccountReceivable\Invoice;
use App\Models\AccountReceivable\Customer;
use App\Models\AccountReceivable\Reminder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\GeneralLedgerService;
use App\Services\SalesSyncService;

class AccountsReceivableController extends Controller
{
    protected GeneralLedgerService $ledger;
    protected SalesSyncService $salesSync;

    public function __construct(GeneralLedgerService $ledger, SalesSyncService $salesSync)
    {
        $this->ledger = $ledger;
        $this->salesSync = $salesSync;
    }

    /**
     * Display the Accounts Receivable dashboard.
     */
    public function dashboard()
    {
        Invoice::where('due_date', '<', now())
            ->where('balance', '>', 0)
            ->update([
                'status' => 'Overdue'
            ]);
$totalReceivable = Invoice::sum('balance');

$totalPaid = Invoice::where('status', 'Paid')
    ->sum(DB::raw('total - balance'));

$totalUnpaid = Invoice::where('status', 'Unpaid')
    ->sum('balance');

$totalPartial = Invoice::where('status', 'Partial')
    ->sum('balance');

$totalOverdue = Invoice::where('status', 'Overdue')
    ->sum('balance');

        $invoices = Invoice::with(['customer', 'items'])
            ->latest()
            ->take(7)
            ->get();

        $outstanding = Invoice::with('customer')
            ->where('balance', '>', 0)
            ->orderByDesc('balance')
            ->take(5)
            ->get();

        $recentPayments = Payment::with([
            'customer',
            'invoice'
        ])
            ->latest()
            ->take(6)
            ->get();

        $allRecentPayments = Payment::with([
            'customer',
            'invoice'
        ])
            ->latest()
            ->get();

        // Aging of Receivables

        $allReceivables = Invoice::where('balance', '>', 0)->get();

        $current = 0;
        $days31_60 = 0;
        $days61_90 = 0;
        $over90 = 0;

        foreach ($allReceivables as $invoice) {

          $days = Carbon::parse($invoice->due_date)
    ->diffInDays(today(), false);

if ($days < 0) {
    // Not yet due
    $current += $invoice->balance;

} elseif ($days <= 30) {
    // 0–30 days overdue
    $current += $invoice->balance;

} elseif ($days <= 60) {
    $days31_60 += $invoice->balance;

} elseif ($days <= 90) {
    $days61_90 += $invoice->balance;

} else {
    $over90 += $invoice->balance;
}
        }

        $customers = Customer::orderBy('customer_name')->get();

        return view('accounts-receivable.dashboard', compact(
            'totalReceivable',
            'totalPaid',
            'totalUnpaid',
            'totalPartial',
            'totalOverdue',
            'invoices',
            'outstanding',
            'customers',
            'recentPayments',
            'allRecentPayments',
            'current',
            'days31_60',
            'days61_90',
            'over90'
        ));
    }

    /**
     * Display the Create Invoice page.
     */
    public function invoice()
    {
        $customers = Customer::all();

        return view('accounts-receivable.invoice', compact('customers'));
    }

    /**
     * Display all invoices.
     */
    public function allInvoices(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $invoices = Invoice::with(['customer', 'items'])            
        ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($customer) use ($search) {

                            $customer->where('customer_name', 'like', "%{$search}%")
                                     ->orWhere('company', 'like', "%{$search}%");

                      });

                });

            })

            ->when($status, function ($query) use ($status) {

                $query->where('status', $status);

            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalInvoices = Invoice::count();

        $paidInvoices = Invoice::where('status', 'Paid')
            ->count();

        $unpaidInvoices = Invoice::where('status', 'Unpaid')
            ->count();

        $overdueInvoices = Invoice::where('status', 'Overdue')
            ->count();

        return view('accounts-receivable.allinvoices', compact(
            'invoices',
            'totalInvoices',
            'paidInvoices',
            'unpaidInvoices',
            'overdueInvoices'
        ));
    }

    /**
     * Save a new Invoice.
     */
    public function storeInvoice(Request $request)
    {
        $request->validate([

            'customer_id' => 'required',
            'invoice_date' => 'required',
            'due_date' => 'required',
            'payment_terms' => 'required',
        ]);

        $invoice = Invoice::create([

            'invoice_number' => 'INV-' . date('YmdHis'),

            'customer_id' => $request->customer_id,

            'invoice_date' => $request->invoice_date,

            'due_date' => $request->due_date,

            'payment_terms' => $request->payment_terms,

            'subtotal' => $request->subtotal,

            'tax' => $request->tax,

            'total' => $request->total,

            'balance' => $request->balance,

            'status' => 'Unpaid',

            'notes' => $request->notes,

        ]);

        foreach ($request->description as $key => $description) {

            if (empty($description)) {
                continue;
            }

            InvoiceItem::create([

                'invoice_id' => $invoice->id,

                'description' => $description,

                'quantity' => $request->quantity[$key],

                'unit_price' => $request->unit_price[$key],

                'amount' =>
                    $request->quantity[$key] *
                    $request->unit_price[$key],
        
            ]);

        }

        // Auto-record this invoice in the General Ledger
        // (Dr Accounts Receivable / Cr Sales Revenue).
        $this->ledger->postInvoiceCreated($invoice);

        return redirect()->route('receivable.allinvoices')
    ->with('success', 'Invoice saved successfully!');
    }

    /**
     * Return invoice details as JSON.
     */
    public function details($id)
    {
        $invoice = Invoice::with([
            'customer',
            'items'
        ])
            ->findOrFail($id);

        return response()->json($invoice);
    }

    /**
     * Display a single invoice.
     */
    public function show($id)
    {
        $invoice = Invoice::with([
            'customer',
            'items'
        ])
            ->findOrFail($id);

        return view('accounts-receivable.viewinvoice', compact('invoice'));
    }

    /**
     * Update an existing invoice.
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            'invoice_date' => 'required',
            'due_date' => 'required',

            'description.*' => 'required',
            'quantity.*' => 'required|numeric',
            'unit_price.*' => 'required|numeric',

        ]);

        $invoice = Invoice::findOrFail($id);

        // How much has already been collected against this invoice via
        // payments BEFORE this edit. We need this so editing line items
        // doesn't wipe out payments that were already applied.
        $alreadyPaid = (float) $invoice->total - (float) $invoice->balance;

        // COMPUTE NEW TOTALS FROM THE EDITED LINE ITEMS

        $subtotal = 0;

        foreach ($request->description as $key => $description) {

            $subtotal +=
                $request->quantity[$key] *
                $request->unit_price[$key];

        }

        $tax = $subtotal * 0.12;

        $total = $subtotal + $tax;

        // Re-apply whatever was already paid against the NEW total instead
        // of resetting the invoice back to fully unpaid.
        $newBalance = max($total - $alreadyPaid, 0);

        if ($newBalance <= 0) {
            $status = 'Paid';
        } elseif ($alreadyPaid > 0) {
            $status = 'Partial';
        } else {
            $status = 'Unpaid';
        }

        // UPDATE INVOICE

        $invoice->update([

            'invoice_date' => $request->invoice_date,

            'due_date' => $request->due_date,

            'subtotal' => $subtotal,

            'tax' => $tax,

            'total' => $total,

            'balance' => $newBalance,

            'status' => $status,

        ]);

        // REMOVE OLD ITEMS

        $invoice->items()->delete();

        // INSERT UPDATED ITEMS

        foreach ($request->description as $key => $description) {

            InvoiceItem::create([

                'invoice_id' => $invoice->id,

                'description' => $description,

                'quantity' => $request->quantity[$key],

                'unit_price' => $request->unit_price[$key],

                'amount' =>
                    $request->quantity[$key] *
                    $request->unit_price[$key],

            ]);

        }

        // Keep the General Ledger in sync with the edited invoice total
        // (Dr Accounts Receivable / Cr Sales Revenue, reposted).
        $this->ledger->postInvoiceUpdated($invoice);

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully',
            'balance' => $newBalance,
            'status' => $status,
        ]);
    }

    /**
     * Delete an invoice.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        // Remove the matching journal entry so the GL doesn't keep a
        // balance for an invoice that no longer exists.
        $this->ledger->reverseInvoice($invoice);

        $invoice->items()->delete();
        $invoice->delete();

        return response()->json([
    'success' => true,
    'message' => 'Invoice deleted successfully.'
]);
    }

    /**
     * Get open invoices for a specific customer.
     */
    public function customerInvoices($id)
    {
        $invoices = Invoice::where('customer_id', $id)
            ->where('balance', '>', 0)
            ->orderBy('due_date')
            ->get();

        return response()->json($invoices);
    }

    /**
     * Record a new payment and apply it against the selected invoices.
     */
    public function storePayment(Request $request)
    {
        $request->validate([

            'invoice_id' => 'required|array',
            'invoice_id.*' => 'exists:ar_invoices,id',
            'customer_id' => 'required',
            'payment_date' => 'required',
            'payment_method' => 'required',
            'amount' => 'required|numeric',

        ]);

        // Save Payment
        $payment = Payment::create([

            'invoice_id' => $request->invoice_id[0],

            'customer_id' => $request->customer_id,

            'payment_date' => $request->payment_date,

            'payment_method' => $request->payment_method,

            'reference_no' => 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT),

            'amount' => $request->amount,

            'remarks' => $request->remarks,

        ]);

        // Update Invoice Balance

        $remainingPayment = $request->amount;

foreach ($request->invoice_id as $invoiceId) {

    $invoice = Invoice::findOrFail($invoiceId);

    $paymentApplied = min($remainingPayment, $invoice->balance);

    $newBalance = $invoice->balance - $paymentApplied;


    if ($newBalance <= 0) {

        $invoice->update([
            'balance' => 0,
            'status' => 'Paid'
        ]);

        // If this invoice originated from the Sales system, push the
        // paid status back so both systems stay in sync.
        if ($invoice->sales_invoice_id) {
            $this->salesSync->markInvoicePaid($invoice->sales_invoice_id);
        }

    } else {

        $invoice->update([
            'balance' => $newBalance,
            'status' => 'Partial'
        ]);

    }


    $remainingPayment -= $paymentApplied;


    if ($remainingPayment <= 0) {
        break;
    }
}

        // Auto-record this payment in the General Ledger
        // (Dr Cash on Hand / Cr Accounts Receivable).
        $this->ledger->postPaymentReceived($payment);

        // If the Record Payment page submitted this via fetch/AJAX (Accept: application/json),
        // return the saved payment so the page can show the success modal without a reload.
        // Classic (non-AJAX) form posts keep the original redirect-back behavior untouched.
        if ($request->wantsJson()) {

            return response()->json([
                'payment_no' => $payment->reference_no,
                'customer'   => optional($payment->customer)->customer_name,
                'amount'     => number_format((float) $payment->amount, 2),
                'date'       => \Carbon\Carbon::parse($payment->payment_date)->format('F j, Y'),
            ]);

        }

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the Payment page.
     */
    public function payment()
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('accounts-receivable.payment', compact('customers'));
    }

    /**
     * Display the Aging Report page.
     */
    public function aging(Request $request)
    {
        // Table data with pagination
        $query = Invoice::with('customer')
            ->where('balance', '>', 0);

        switch ($request->filter) {

            case 'today':
                $query->whereDate('invoice_date', today());
                break;

            case 'week':
                $query->whereBetween('invoice_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;

            case 'month':
                $query->whereMonth('invoice_date', now()->month)
                      ->whereYear('invoice_date', now()->year);
                break;

            case 'year':
                $query->whereYear('invoice_date', now()->year);
                break;

            case 'last_month':
                $query->whereMonth('invoice_date', now()->subMonth()->month)
                      ->whereYear('invoice_date', now()->subMonth()->year);
                break;

            case 'all':
            default:
                break;
        }

        $receivables = $query->latest()->paginate(5)->withQueryString();
        $invoicesData = Invoice::with('customer')
    ->where('balance', '>', 0)
    ->get();

        // All outstanding invoices for computation
        $allReceivables = Invoice::where('balance', '>', 0)->get();

        $current = 0;
        $days31_60 = 0;
        $days61_90 = 0;
        $over90 = 0;

        foreach ($allReceivables as $invoice) {

            $days = \Carbon\Carbon::parse($invoice->due_date)
                ->diffInDays(today(), false);

            if ($days <= 30) {

                // Current (0-30 Days)
                $current += $invoice->balance;

            } elseif ($days <= 60) {

                // 31-60 Days
                $days31_60 += $invoice->balance;

            } elseif ($days <= 90) {

                // 61-90 Days
                $days61_90 += $invoice->balance;

            } else {

                // 90+ Days
                $over90 += $invoice->balance;

            }
        }

        $totalOutstanding =
            $current +
            $days31_60 +
            $days61_90 +
            $over90;


return view('accounts-receivable.aging', compact(
     'receivables',
    'invoicesData',
    'totalOutstanding',
    'current',
    'days31_60',
    'days61_90',
    'over90'
));
    }

    /**
     * Export the Aging Report as an Excel file.
     */
   public function exportExcel()
{
    $filename = 'aging-report.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$filename",
    ];

    $callback = function () {
        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'Invoice No',
            'Customer',
            'Invoice Date',
            'Due Date',
            'Balance',
            'Status'
        ]);

        $receivables = Invoice::with('customer')
            ->where('balance', '>', 0)
            ->get();

        foreach ($receivables as $invoice) {
            fputcsv($file, [
                $invoice->invoice_number,
                $invoice->customer->customer_name,
                $invoice->invoice_date,
                $invoice->due_date,
                $invoice->balance,
                $invoice->status,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    /**
     * Export the Aging Report as a PDF file.
     */
    public function exportPdf()
    {
        $receivables = Invoice::with('customer')
            ->where('balance', '>', 0)
            ->latest()
            ->get();

        $current = 0;
        $days31_60 = 0;
        $days61_90 = 0;
        $over90 = 0;

        foreach ($receivables as $invoice) {

            $days = \Carbon\Carbon::parse($invoice->due_date)
                ->diffInDays(today(), false);

            if ($days <= 30) {

                $current += $invoice->balance;

            } elseif ($days <= 60) {

                $days31_60 += $invoice->balance;

            } elseif ($days <= 90) {

                $days61_90 += $invoice->balance;

            } else {

                $over90 += $invoice->balance;

            }

        }

        $totalOutstanding =
            $current +
            $days31_60 +
            $days61_90 +
            $over90;

        $pdf = Pdf::loadView('accounts-receivable.agingpdf', compact(
            'receivables',
            'totalOutstanding',
            'current',
            'days31_60',
            'days61_90',
            'over90'
        ));

        return $pdf->download('aging-report.pdf');
    }

    /**
     * Send/record a reminder for an invoice.
     */
    public function storeReminder(Request $request)
{
    $request->validate([
        'customer_id' => 'required',
        'invoice_id' => 'required',
        'message' => 'required',
    ]);

    Reminder::create([
        'customer_id' => $request->customer_id,
        'invoice_id'  => $request->invoice_id,
        'status'      => 'Sent',
        'message'     => $request->message,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Reminder sent successfully!',
    ]);
}

public function reminderHistory()
{
    $history = Reminder::join('ar_customers', 'ar_customers.id', '=', 'ar_reminders.customer_id')
        ->join('ar_invoices', 'ar_invoices.id', '=', 'ar_reminders.invoice_id')
        ->select(
            'ar_customers.customer_name',
            'ar_invoices.invoice_number',
            'ar_invoices.balance as amount',
            'ar_reminders.message',
            'ar_reminders.status',
            'ar_reminders.created_at as sent_at',
            DB::raw("'email' as channel")
        )
        ->orderByDesc('ar_reminders.created_at')
        ->get();

    return response()->json($history);
}

    /**
     * Generate a filtered Accounts Receivable report.
     */
    public function generateReport(Request $request)
    {
        $query = Invoice::with('customer');

        // Filter by Report Type
        $reportType = $request->report_type;

        switch ($reportType) {

            case 'Outstanding Invoices':
                $query->where('balance', '>', 0);
                break;

            case 'Customer Statement':
                // Customer filter lang
                break;

            case 'Collection Report':
                $query->where('status', 'Paid');
                break;

            case 'Accounts Receivable Summary':
            default:
                // Lahat ng invoices
                break;
        }

        // Filter by Customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by Date
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->get();

        return view('accounts-receivable.results', [
            'invoices' => $invoices,
            'reportType' => $request->report_type,
        ]);
    }

    /**
     * Export the filtered Accounts Receivable report as a PDF.
     */
    public function exportReportPdf(Request $request)
    {
        $query = Invoice::with('customer');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        switch ($request->report_type) {

            case 'Outstanding Invoices':
                $query->where('balance', '>', 0);
                break;

            case 'Customer Statement':
                break;

            case 'Collection Report':
                $query->where('status', 'Paid');
                break;
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->get();

        $pdf = Pdf::loadView('accounts-receivable.pdf', [
            'invoices' => $invoices,
            'reportType' => $request->report_type
        ]);

        return $pdf->download('accounts-receivable.pdf');
    }
}