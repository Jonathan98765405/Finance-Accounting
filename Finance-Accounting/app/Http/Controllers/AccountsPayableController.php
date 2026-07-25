<?php

namespace App\Http\Controllers;

use App\Mail\RemittanceAdviceMail;
use App\Models\AccountPayable\Activity;
use App\Models\AccountPayable\GoodsReceipt;
use App\Models\AccountPayable\Invoice;
use App\Models\AccountPayable\Payment;
use App\Models\AccountPayable\PurchaseOrder;
use App\Models\AccountPayable\Supplier;
use App\Services\GeneralLedgerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AccountsPayableController extends Controller
{
    public function __construct(protected GeneralLedgerService $glPosting)
    {
    }

    /**
     * GET /account-payable  -> ap.dashboard
     */
    public function dashboard()
    {
        $totalAP = Invoice::whereNotIn('status', ['paid'])->sum('total_amount');

        $dueThisMonth = Invoice::whereNotIn('status', ['paid'])
            ->whereMonth('due_date', now()->month)
            ->whereYear('due_date', now()->year)
            ->sum('total_amount');

        $overdue = Invoice::whereNotIn('status', ['paid'])
            ->where('due_date', '<', now())
            ->sum('total_amount');

        $paidThisMonth = Invoice::where('status', 'paid')
            ->whereMonth('updated_at', now()->month)
            ->count();
        $dueOrPaidThisMonth = Invoice::whereMonth('due_date', now()->month)->count();
        $onTimeRate = $dueOrPaidThisMonth > 0
            ? round(($paidThisMonth / max($dueOrPaidThisMonth, 1)) * 100)
            : 0;

        // AP Aging buckets, based on days past due_date
        $openInvoices = Invoice::whereNotIn('status', ['paid'])->get(['due_date', 'total_amount']);
        $aging = ['0_30' => 0, '31_60' => 0, '61_90' => 0, '90_plus' => 0];

        foreach ($openInvoices as $inv) {
            $daysPastDue = $inv->due_date ? now()->diffInDays($inv->due_date, false) * -1 : 0;

            if ($daysPastDue <= 30) {
                $aging['0_30'] += $inv->total_amount;
            } elseif ($daysPastDue <= 60) {
                $aging['31_60'] += $inv->total_amount;
            } elseif ($daysPastDue <= 90) {
                $aging['61_90'] += $inv->total_amount;
            } else {
                $aging['90_plus'] += $inv->total_amount;
            }
        }

        // FIX: total_due and due_this_month previously had no status filter,
        // so they summed ALL invoices per supplier (including already-paid
        // ones) while $totalAP above only counts open invoices. That mismatch
        // is why a single vendor's "Total Due" could exceed the company-wide
        // Total AP. Both subqueries now exclude paid invoices, consistent
        // with $totalAP and with the existing "overdue" subquery below.
        $topVendors = Supplier::withSum(['invoices as total_due' => function ($q) {
                $q->whereNotIn('status', ['paid']);
            }], 'total_amount')
            ->withSum(['invoices as due_this_month' => function ($q) {
                $q->whereNotIn('status', ['paid'])
                    ->whereMonth('due_date', now()->month)
                    ->whereYear('due_date', now()->year);
            }], 'total_amount')
            ->withSum(['invoices as overdue' => function ($q) {
                $q->where('due_date', '<', now())->whereNotIn('status', ['paid']);
            }], 'total_amount')
            ->having('total_due', '>', 0)
            ->orderByDesc('total_due')
            ->get();

        $recentActivities = Activity::latest()->take(6)->get();

        return view('accounts-payable.dashboard', compact(
            'totalAP', 'dueThisMonth', 'overdue', 'onTimeRate', 'aging', 'topVendors', 'recentActivities'
        ));
    }

    /**
     * GET /account-payable/record-invoice  -> ap.record
     */
    public function createInvoice()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::orderByDesc('po_date')->get();

        return view('accounts-payable.record-invoice', compact('suppliers', 'purchaseOrders'));
    }

    /**
     * POST /account-payable/record-invoice  -> ap.record.store
     */
    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:ap_suppliers,id',
            'invoice_number' => 'required|string|unique:ap_invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'po_number' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'currency' => 'nullable|string',
            'department' => 'nullable|string',
            'supplier_reference' => 'nullable|string',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = collect($validated['items'])->sum(fn ($i) => $i['quantity'] * $i['unit_price']);
        $tax = round($subtotal * 0.12, 2);

        $invoice = Invoice::create([
            'invoice_number' => $validated['invoice_number'],
            'supplier_id' => $validated['supplier_id'],
            'purchase_order_id' => optional(
                PurchaseOrder::where('po_number', $validated['po_number'] ?? null)->first()
            )->id,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'payment_terms' => $validated['payment_terms'] ?? 'Net 30',
            'currency' => $validated['currency'] ?? 'PHP',
            'department' => $validated['department'] ?? null,
            'supplier_reference' => $validated['supplier_reference'] ?? null,
            'status' => 'pending_verification',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => 0,
            'total_amount' => $subtotal + $tax,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        Activity::create([
            'invoice_id' => $invoice->id,
            'description' => "Invoice {$invoice->invoice_number} from {$invoice->supplier->name} received.",
            'type' => 'invoice_received',
            'status' => 'done',
        ]);

        return redirect()->route('ap.review', $invoice)->with('success', 'Invoice recorded successfully.');
    }

    /**
     * DELETE /account-payable/invoice/{invoice}  -> ap.invoice.destroy
     *
     * Deleting an invoice must also undo whatever it (and any payments
     * made against it) posted to the General Ledger — otherwise the GL
     * keeps stale liability/expense/cash lines for a bill that no
     * longer exists in AP.
     */
    public function destroyInvoice(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            // Reverse every payment's cash-settlement entry first...
            foreach ($invoice->payments as $payment) {
                $this->glPosting->reversePaymentCompleted($payment);
            }

            // ...then the invoice's own liability entry.
            $this->glPosting->reverseInvoiceApproved($invoice);

            $invoice->payments()->delete();
            $invoice->items()->delete();
            $invoice->delete();
        });

        return redirect()->route('ap.dashboard')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * GET /account-payable/review-invoice/{invoice?}  -> ap.review
     */
    public function reviewInvoice(?Invoice $invoice = null)
    {
        if ($invoice) {
            session(['ap.last_review_invoice_id' => $invoice->id]);
        } else {
            $invoice = Invoice::find(session('ap.last_review_invoice_id'));

            if (! $invoice || $invoice->status !== 'pending_verification') {
                $invoice = Invoice::where('status', 'pending_verification')->oldest()->firstOrFail();
            }
        }

        $invoice->load('supplier', 'purchaseOrder', 'documents', 'items');

        return view('accounts-payable.review-invoice', compact('invoice'));
    }

    /**
     * POST /account-payable/review-invoice/{invoice}/verify  -> ap.review.verify
     */
    public function verifyInvoice(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'verification_remarks' => 'nullable|string',
        ]);

        $invoice->update([
            'status' => 'verified',
            'verification_remarks' => $validated['verification_remarks'] ?? null,
        ]);

        Activity::create([
            'invoice_id' => $invoice->id,
            'description' => "Invoice {$invoice->invoice_number} verified and sent to Three-Way Match.",
            'type' => 'invoice_verified',
            'status' => 'done',
        ]);

        return redirect()->route('ap.match', $invoice)->with('success', 'Invoice verified.');
    }

    /**
     * POST /account-payable/review-invoice/{invoice}/reject  -> ap.review.reject
     */
    public function rejectInvoice(Request $request, Invoice $invoice)
    {
        $validated = $request->validate(['verification_remarks' => 'nullable|string']);

        $invoice->update([
            'status' => 'rejected',
            'verification_remarks' => $validated['verification_remarks'] ?? null,
        ]);

        Activity::create([
            'invoice_id' => $invoice->id,
            'description' => "Invoice {$invoice->invoice_number} rejected during review.",
            'type' => 'invoice_rejected',
            'status' => 'done',
        ]);

        return redirect()->route('ap.dashboard')->with('success', 'Invoice rejected.');
    }

    /**
     * GET /account-payable/three-way-match-queue  -> ap.match.pending
     *
     * Lists every invoice currently awaiting three-way match, so the user
     * can jump to a specific one by ID instead of relying on the
     * "oldest verified" fallback used by threeWayMatch().
     */
    public function pendingMatches()
    {
        $invoices = Invoice::where('status', 'verified')
            ->with('supplier', 'purchaseOrder.goodsReceipts')
            ->oldest()
            ->get()
            ->map(function (Invoice $invoice) {
                $grn = $invoice->purchaseOrder?->goodsReceipts->first();

                // Compare against subtotal (VAT-exclusive), not total_amount,
                // since PO/GRN totals from Procurement don't include the 12%
                // VAT that gets added when the invoice is recorded.
                $invoice->po_matched_live = $invoice->purchaseOrder
                    && abs((float) $invoice->purchaseOrder->total_amount - (float) $invoice->subtotal) < 0.01;

                $invoice->grn_matched_live = $grn
                    && abs((float) $grn->total_amount - (float) $invoice->subtotal) < 0.01;

                return $invoice;
            });

        return view('accounts-payable.pending-matches', compact('invoices'));
    }

    /**
     * GET /account-payable/three-way-match/{invoice?}  -> ap.match
     */
    public function threeWayMatch(?Invoice $invoice = null)
    {
        if ($invoice) {
            session(['ap.last_match_invoice_id' => $invoice->id]);
        } else {
            $invoice = Invoice::find(session('ap.last_match_invoice_id'));

            if (! $invoice || $invoice->status !== 'verified') {
                $invoice = Invoice::where('status', 'verified')->oldest()->first();
            }
        }

        if (! $invoice) {
            return redirect()->route('ap.match.pending')
                ->with('success', 'Nothing is waiting on a three-way match right now.');
        }

        $invoice->load('supplier', 'purchaseOrder.goodsReceipts', 'items');
        $goodsReceipt = $invoice->purchaseOrder?->goodsReceipts->first();

        // Compare against subtotal (VAT-exclusive), not total_amount,
        // since PO/GRN totals from Procurement don't include the 12%
        // VAT that gets added when the invoice is recorded.
        $poMatched = $invoice->purchaseOrder
            && abs((float) $invoice->purchaseOrder->total_amount - (float) $invoice->subtotal) < 0.01;

        $grnMatched = $goodsReceipt
            && abs((float) $goodsReceipt->total_amount - (float) $invoice->subtotal) < 0.01;

        $purchaseOrders = PurchaseOrder::where('supplier_id', $invoice->supplier_id)
            ->orderByDesc('po_date')
            ->get();

        return view('accounts-payable.three-way-match', compact('invoice', 'goodsReceipt', 'poMatched', 'grnMatched', 'purchaseOrders'));
    }

    /**
     * POST /account-payable/three-way-match/{invoice}/link-po  -> ap.match.link-po
     */
    public function linkPurchaseOrder(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'nullable|exists:ap_purchase_orders,id',
        ]);

        $invoice->update(['purchase_order_id' => $validated['purchase_order_id'] ?? null]);

        Activity::create([
            'invoice_id' => $invoice->id,
            'description' => ($validated['purchase_order_id'] ?? null)
                ? "Purchase order linked to invoice {$invoice->invoice_number}."
                : "Purchase order unlinked from invoice {$invoice->invoice_number}.",
            'type' => 'po_linked',
            'status' => 'done',
        ]);

        $message = ($validated['purchase_order_id'] ?? null)
            ? 'Purchase order linked. Review the match below.'
            : 'Purchase order unlinked.';

        return redirect()->route('ap.match', $invoice)->with('success', $message);
    }

    /**
     * POST /account-payable/three-way-match/{invoice}/approve  -> ap.match.approve
     */
    public function approveMatch(Invoice $invoice)
    {
        $invoice->update([
            'po_matched' => true,
            'grn_matched' => true,
            'invoice_matched' => true,
            'match_result' => 'APPROVED',
            'status' => 'approved',
        ]);

        Activity::create([
            'invoice_id' => $invoice->id,
            'description' => "Three-way match completed for {$invoice->invoice_number}.",
            'type' => 'three_way_match',
            'status' => 'done',
        ]);

        // Recognize the liability in the General Ledger:
        // Dr. Expense / Cr. Accounts Payable
        $this->glPosting->postInvoiceApproved($invoice->load('supplier'));

        return redirect()->route('ap.schedule')->with('success', 'Match approved.');
    }

    /**
     * POST /account-payable/three-way-match/{invoice}/draft  -> ap.match.draft
     */
    public function saveMatchDraft(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'match_notes' => 'nullable|string',
        ]);

        $invoice->update([
            'remarks' => $validated['match_notes'] ?? null,
        ]);

        return back()->with('success', 'Draft saved. Nothing has been approved yet.');
    }

    /**
     * POST /account-payable/three-way-match/{invoice}/clarify  -> ap.match.clarify
     */
    public function requestMatchClarification(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'match_notes' => 'nullable|string',
        ]);

        $invoice->update([
            'remarks' => $validated['match_notes'] ?? null,
            'status' => 'clarification_requested',
        ]);

        Activity::create([
            'invoice_id' => $invoice->id,
            'description' => "Clarification requested for {$invoice->invoice_number}.",
            'type' => 'clarification_requested',
            'status' => 'done',
        ]);

        return back()->with('warning', 'Clarification requested — the invoice status has been updated.');
    }

    /**
     * GET /account-payable/purchase-orders  -> ap.po.index
     */
    public function purchaseOrders()
    {
        $purchaseOrders = PurchaseOrder::with('supplier', 'goodsReceipts', 'items')
            ->latest() // newest created record first, not newest po_date
            ->get();

        return view('accounts-payable.purchase-orders', compact('purchaseOrders'));
    }

    /**
     * GET /account-payable/purchase-orders/create  -> ap.po.create
     */
    public function createPurchaseOrder()
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('accounts-payable.purchase-order-create', compact('suppliers'));
    }

    /**
     * POST /account-payable/purchase-orders  -> ap.po.store
     */
    public function storePurchaseOrder(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:ap_suppliers,id',
            'po_number' => 'required|string|unique:ap_purchase_orders,po_number',
            'po_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Subtotal + 12% VAT, same formula as storeInvoice(), so a PO's
        // total_amount lines up with the invoice total it's meant to match.
        $subtotal = collect($validated['items'])->sum(fn ($i) => $i['quantity'] * $i['unit_price']);
        $total = $subtotal + round($subtotal * 0.12, 2);

        $po = PurchaseOrder::create([
            'po_number' => $validated['po_number'],
            'supplier_id' => $validated['supplier_id'],
            'po_date' => $validated['po_date'],
            'total_amount' => $total,
            'status' => 'open',
        ]);

        foreach ($validated['items'] as $item) {
            $po->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        Activity::create([
            'description' => "Purchase order {$po->po_number} created for {$po->supplier->name}.",
            'type' => 'po_created',
            'status' => 'done',
        ]);

        return redirect()->route('ap.po.index')->with('success', 'Purchase order created successfully.');
    }

    /**
     * GET /account-payable/purchase-orders/{purchaseOrder}/edit  -> ap.po.edit
     */
    public function editPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items', 'supplier');
        $suppliers = Supplier::orderBy('name')->get();

        return view('accounts-payable.purchase-order-edit', compact('purchaseOrder', 'suppliers'));
    }

    /**
     * PUT /account-payable/purchase-orders/{purchaseOrder}  -> ap.po.update
     */
    public function updatePurchaseOrder(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:ap_suppliers,id',
            'po_number' => 'required|string|unique:ap_purchase_orders,po_number,' . $purchaseOrder->id,
            'po_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = collect($validated['items'])->sum(fn ($i) => $i['quantity'] * $i['unit_price']);
        $total = $subtotal + round($subtotal * 0.12, 2);

        $purchaseOrder->update([
            'po_number' => $validated['po_number'],
            'supplier_id' => $validated['supplier_id'],
            'po_date' => $validated['po_date'],
            'total_amount' => $total,
        ]);

        // Simplest correct way to sync line items on edit: replace them.
        // Fine here since PO items have no other table pointing at their IDs.
        $purchaseOrder->items()->delete();

        foreach ($validated['items'] as $item) {
            $purchaseOrder->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        Activity::create([
            'description' => "Purchase order {$purchaseOrder->po_number} updated.",
            'type' => 'po_updated',
            'status' => 'done',
        ]);

        return redirect()->route('ap.po.index')->with('success', 'Purchase order updated successfully.');
    }

    /**
     * DELETE /account-payable/purchase-orders/{purchaseOrder}  -> ap.po.destroy
     */
    public function destroyPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->goodsReceipts()->exists() || $purchaseOrder->invoices()->exists()) {
            return back()->with('warning', "Purchase order {$purchaseOrder->po_number} can't be deleted — it already has a goods receipt or invoice linked to it.");
        }

        $poNumber = $purchaseOrder->po_number;
        $purchaseOrder->delete(); // items cascade-delete via the FK constraint

        Activity::create([
            'description' => "Purchase order {$poNumber} deleted.",
            'type' => 'po_deleted',
            'status' => 'done',
        ]);

        return redirect()->route('ap.po.index')->with('success', 'Purchase order deleted.');
    }

    /**
     * GET /account-payable/goods-receipts/create/{purchaseOrder?}  -> ap.grn.create
     */
    public function createGoodsReceipt(?PurchaseOrder $purchaseOrder = null)
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->orderByDesc('po_date')->get();

        return view('accounts-payable.goods-receipt-create', compact('purchaseOrders', 'purchaseOrder'));
    }

    /**
     * POST /account-payable/goods-receipts  -> ap.grn.store
     */
    public function storeGoodsReceipt(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:ap_purchase_orders,id',
            'grn_number' => 'required|string|unique:ap_goods_receipts,grn_number',
            'receipt_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $grn = GoodsReceipt::create($validated);
        $grn->load('purchaseOrder');
        $grn->purchaseOrder->update(['status' => 'received']);

        Activity::create([
            'description' => "Goods receipt {$grn->grn_number} recorded for {$grn->purchaseOrder->po_number}.",
            'type' => 'goods_received',
            'status' => 'done',
        ]);

        return redirect()->route('ap.po.index')->with('success', 'Goods receipt recorded successfully.');
    }

    /**
     * GET /account-payable/schedule-payment  -> ap.schedule
     */
    public function schedulePayment()
    {
        $readyInvoices = Invoice::with('supplier')
            ->where('status', 'approved')
            ->orderBy('due_date')
            ->get();

        $scheduledPayments = Payment::with('invoice.supplier')
            ->where('status', 'scheduled')
            ->orderBy('payment_date')
            ->get();

        // "Tracked" = still unpaid and already past Three-Way Match: either
        // waiting to be scheduled (approved) or already scheduled.
        $trackedInvoices = Invoice::whereIn('status', ['approved', 'scheduled'])
            ->get(['id', 'due_date', 'total_amount']);

        $totalOutstanding = $trackedInvoices->sum('total_amount');

        $weekEnd = now()->copy()->addDays(7)->endOfDay();
        $dueThisWeekInvoices = $trackedInvoices->filter(
            fn ($inv) => $inv->due_date && $inv->due_date->between(now()->startOfDay(), $weekEnd)
        );
        $dueThisWeek = $dueThisWeekInvoices->sum('total_amount');
        $dueThisWeekCount = $dueThisWeekInvoices->count();

        $dueThisMonthInvoices = $trackedInvoices->filter(
            fn ($inv) => $inv->due_date && $inv->due_date->isSameMonth(now()) && $inv->due_date->isSameYear(now())
        );
        $dueThisMonth = $dueThisMonthInvoices->sum('total_amount');
        $dueThisMonthCount = $dueThisMonthInvoices->count();

        $overdueInvoices = $trackedInvoices->filter(fn ($inv) => $inv->due_date && $inv->due_date->isPast());
        $overdue = $overdueInvoices->sum('total_amount');
        $overdueCount = $overdueInvoices->count();

        $totalScheduled = $scheduledPayments->sum('amount');

        // Payment calendar for the current month, colour-coded by the
        // highest-priority scheduled payment due that day.
        $calendarMonth = now()->startOfMonth();
        $priorityRank = ['low' => 1, 'medium' => 2, 'high' => 3];
        $priorityByDay = [];

        foreach ($scheduledPayments as $payment) {
            if ($payment->payment_date && $payment->payment_date->isSameMonth($calendarMonth)) {
                $day = $payment->payment_date->day;
                $existing = $priorityByDay[$day] ?? null;
                if (! $existing || ($priorityRank[$payment->priority] ?? 0) > ($priorityRank[$existing] ?? 0)) {
                    $priorityByDay[$day] = $payment->priority;
                }
            }
        }

        $calendarCells = array_fill(0, $calendarMonth->dayOfWeek, null);
        for ($d = 1; $d <= $calendarMonth->daysInMonth; $d++) {
            $calendarCells[] = [
                'day' => $d,
                'priority' => $priorityByDay[$d] ?? null,
                'isToday' => $d === now()->day,
            ];
        }
        while (count($calendarCells) % 7 !== 0) {
            $calendarCells[] = null;
        }

        return view('accounts-payable.schedule-payment', compact(
            'readyInvoices', 'scheduledPayments', 'totalOutstanding',
            'dueThisWeek', 'dueThisWeekCount', 'dueThisMonth', 'dueThisMonthCount',
            'overdue', 'overdueCount', 'totalScheduled', 'calendarCells', 'calendarMonth'
        ));
    }

    /**
     * POST /account-payable/schedule-payment/{invoice}  -> ap.schedule.store
     */
    public function storeSchedule(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'priority' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $payment = $invoice->payments()->create([
            'reference_number' => 'PAY-' . now()->format('Y') . '-' . str_pad((string) (Payment::max('id') + 1), 3, '0', STR_PAD_LEFT),
            'payment_date' => $validated['payment_date'],
            'amount' => $invoice->total_amount,
            'payment_method' => $validated['payment_method'],
            'priority' => $validated['priority'],
            'status' => 'scheduled',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        $invoice->update(['status' => 'scheduled']);

        Activity::create([
            'invoice_id' => $invoice->id,
            'description' => "Payment scheduled for {$invoice->invoice_number} on " . Carbon::parse($validated['payment_date'])->format('F j, Y') . '.',
            'type' => 'payment_scheduled',
            'status' => 'scheduled',
        ]);

        return redirect()->route('ap.schedule')->with('success', 'Payment scheduled.');
    }

    /**
     * GET /account-payable/payment-processing  -> ap.payment
     */
    public function paymentProcessing()
    {
        $payments = Payment::with('invoice.supplier')
            ->orderByRaw("FIELD(status, 'processing', 'scheduled', 'approved', 'paid')")
            ->orderBy('payment_date')
            ->get();

        $readyPayments = $payments->filter(fn ($p) => $p->status !== 'paid');
        $paidPayments = $payments->filter(fn ($p) => $p->status === 'paid');

        $totalReady = $readyPayments->sum('amount');
        $totalReadyCount = $readyPayments->count();

        $processedThisMonth = $paidPayments->filter(
            fn ($p) => $p->payment_date && $p->payment_date->isSameMonth(now()) && $p->payment_date->isSameYear(now())
        );
        $processedThisMonthTotal = $processedThisMonth->sum('amount');
        $processedThisMonthCount = $processedThisMonth->count();

        // Paid, but the Remittance Advice hasn't been emailed to the supplier yet.
        // (There's no separate "approval" status in this schema, so this KPI
        // stands in for the design's "Pending Approvals" card.)
        $pendingRemittance = $paidPayments->filter(fn ($p) => is_null($p->remittance_sent_at));
        $pendingRemittanceTotal = $pendingRemittance->sum('amount');
        $pendingRemittanceCount = $pendingRemittance->count();

        $remittanceSentThisMonthCount = $paidPayments->filter(
            fn ($p) => $p->remittance_sent_at
                && $p->remittance_sent_at->isSameMonth(now())
                && $p->remittance_sent_at->isSameYear(now())
        )->count();

        $recentActivities = Activity::whereIn('type', ['payment_scheduled', 'payment_completed', 'remittance_sent'])
            ->latest()
            ->take(4)
            ->get();

        return view('accounts-payable.payment-processing', compact(
            'payments', 'readyPayments', 'paidPayments',
            'totalReady', 'totalReadyCount',
            'processedThisMonthTotal', 'processedThisMonthCount',
            'pendingRemittanceTotal', 'pendingRemittanceCount',
            'remittanceSentThisMonthCount', 'recentActivities'
        ));
    }

    /**
     * POST /account-payable/payment-processing/{payment}/process  -> ap.payment.process
     */
    public function processPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'bank_account' => 'nullable|string',
            'reference_number' => 'nullable|string',
        ]);

        $payment->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'bank_account' => $validated['bank_account'] ?? $payment->bank_account,
            'reference_number' => $validated['reference_number'] ?? $payment->reference_number,
        ]);

        $payment->invoice->update(['status' => 'paid']);

        Activity::create([
            'invoice_id' => $payment->invoice_id,
            'description' => "Payment of {$payment->invoice->currency} " . number_format((float) $payment->amount, 2) . " to {$payment->invoice->supplier->name}, completed.",
            'type' => 'payment_completed',
            'status' => 'done',
        ]);

        // Settle the liability in the General Ledger:
        // Dr. Accounts Payable / Cr. Cash
        $this->glPosting->postPaymentCompleted($payment->load('invoice.supplier'));

        return redirect()->route('ap.payment')->with('success', 'Payment processed.');
    }

    /**
     * GET /account-payable/payment-processing/{payment}/remittance  -> ap.payment.remittance
     *
     * Generates (or re-uses) the Remittance Advice PDF and streams it as a download.
     */
    public function downloadRemittance(Payment $payment)
    {
        $payment->load('invoice.supplier');

        $this->ensureRemittancePdf($payment);

        return response()->download(
            Storage::disk('local')->path($payment->remittance_pdf_path),
            $payment->remittance_number . '.pdf'
        );
    }

    /**
     * POST /account-payable/payment-processing/{payment}/email-remittance  -> ap.payment.remittance.email
     */
    public function emailRemittance(Request $request, Payment $payment)
    {
        $payment->load('invoice.supplier');

        $validated = $request->validate([
            'recipient' => 'nullable|email',
            'subject' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        // The supplier's email is normally the recipient, but it can be
        // blank if the vendor record was created without one. Mail::to()
        // throws a hard 500 (LogicException: "must have a To, Cc, or Bcc
        // header") if it ends up with an empty address, so fail with a
        // clear, recoverable message instead of crashing the request.
        $recipient = $validated['recipient'] ?? $payment->invoice->supplier->email;

        if (! $recipient) {
            return redirect()->route('ap.payment')->with(
                'error',
                "Cannot send remittance advice: {$payment->invoice->supplier->name} has no email address on file. Add one to the supplier record and try again."
            );
        }

        $this->ensureRemittancePdf($payment);

        $subject = $validated['subject'] ?? "Remittance Advice - Invoice {$payment->invoice->invoice_number}";
        $body = $validated['message'] ?? "Please find attached the remittance advice for invoice {$payment->invoice->invoice_number}.";

        Mail::to($recipient)->send(new RemittanceAdviceMail($payment, $subject, $body));

        $payment->update([
            'remittance_sent_at' => now(),
            'remittance_sent_to' => $recipient,
        ]);

        Activity::create([
            'invoice_id' => $payment->invoice_id,
            'description' => "Remittance advice {$payment->remittance_number} emailed to {$payment->invoice->supplier->name}.",
            'type' => 'remittance_sent',
            'status' => 'done',
        ]);

        return redirect()->route('ap.payment')->with([
            'success' => 'Remittance advice sent.',
            'remittance_sent' => [
                'to' => $recipient,
                'invoice' => $payment->invoice->invoice_number,
                'attachment' => $payment->remittance_number . '.pdf',
            ],
        ]);
    }

    /**
     * Make sure a payment has a remittance number and a generated PDF on disk,
     * creating both if this is the first time either is requested.
     */
    protected function ensureRemittancePdf(Payment $payment): void
    {
        if (! $payment->remittance_number) {
            $payment->update([
                'remittance_number' => 'RA-' . now()->format('Y') . '-' . str_pad((string) $payment->id, 3, '0', STR_PAD_LEFT),
            ]);
        }

        if (! $payment->remittance_pdf_path || ! Storage::disk('local')->exists($payment->remittance_pdf_path)) {
            $pdf = Pdf::loadView('pdf.remittance-advice', ['payment' => $payment]);
            $path = 'remittances/' . $payment->remittance_number . '.pdf';
            Storage::disk('local')->put($path, $pdf->output());
            $payment->update(['remittance_pdf_path' => $path]);
        }
    }
}