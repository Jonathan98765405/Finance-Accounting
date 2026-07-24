<?php

namespace App\Http\Controllers\Api\V1\AccountsPayable;

use App\Http\Controllers\Controller;
use App\Models\AccountPayable\GoodsReceipt;
use App\Models\AccountPayable\Invoice;
use App\Models\AccountPayable\Payment;
use App\Models\AccountPayable\PurchaseOrder;
use App\Models\AccountPayable\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsPayableApiController extends Controller
{
    // =========================================================================
    // 1. DASHBOARD METRICS
    // =========================================================================
    public function dashboard()
    {
        return response()->json([
            'total_pending_pos'     => PurchaseOrder::where('status', 'pending')->count(),
            'total_unpaid_invoices' => Invoice::where('payment_status', 'unpaid')->count(),
            'pending_3way_matches'  => Invoice::where('match_status', 'pending')->count(),
            'total_amount_due'      => Invoice::where('payment_status', 'unpaid')->sum('amount'),
            'recent_pos'            => PurchaseOrder::with('supplier')->latest()->take(5)->get(),
        ]);
    }

    // =========================================================================
    // 2. PURCHASE ORDERS (PO)
    // =========================================================================
    public function poIndex()
    {
        return response()->json(
            PurchaseOrder::with(['supplier', 'items'])->latest()->paginate(15)
        );
    }

    public function poStore(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'              => 'required|exists:suppliers,id',
            'order_date'               => 'required|date',
            'items'                    => 'required|array|min:1',
            'items.*.item_description' => 'required|string',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.unit_price'       => 'required|numeric|min:0',
        ]);

        $po = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $taxAmount = $subtotal * 0.12; // 12% VAT
            $totalAmount = $subtotal + $taxAmount;

            $purchaseOrder = PurchaseOrder::create([
                'po_number'    => 'PO-' . strtoupper(uniqid()),
                'supplier_id'  => $validated['supplier_id'],
                'order_date'   => $validated['order_date'],
                'status'       => 'pending',
                'subtotal'     => $subtotal,
                'tax_amount'   => $taxAmount,
                'total_amount' => $totalAmount,
            ]);

            foreach ($validated['items'] as $item) {
                $purchaseOrder->items()->create([
                    'item_description' => $item['item_description'],
                    'quantity'         => $item['quantity'],
                    'unit_price'       => $item['unit_price'],
                    'total_price'      => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $purchaseOrder;
        });

        return response()->json([
            'message' => 'Purchase order created successfully.',
            'data'    => $po->load('items', 'supplier')
        ], 201);
    }

    public function poShow(PurchaseOrder $purchaseOrder)
    {
        return response()->json($purchaseOrder->load('supplier', 'items', 'goodsReceipts'));
    }

    public function poApprove(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Purchase order approved successfully.',
            'data'    => $purchaseOrder
        ]);
    }

    // =========================================================================
    // 3. GOODS RECEIPT NOTES (GRN)
    // =========================================================================
    public function grnIndex()
    {
        return response()->json(
            GoodsReceipt::with(['purchaseOrder', 'items'])->latest()->paginate(15)
        );
    }

    public function grnStore(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id'             => 'required|exists:purchase_orders,id',
            'received_date'                 => 'required|date',
            'received_by'                   => 'required|string',
            'items'                         => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received'     => 'required|integer|min:1',
        ]);

        $grn = DB::transaction(function () use ($validated) {
            $goodsReceipt = GoodsReceipt::create([
                'grn_number'        => 'GRN-' . strtoupper(uniqid()),
                'purchase_order_id' => $validated['purchase_order_id'],
                'received_date'     => $validated['received_date'],
                'received_by'       => $validated['received_by'],
                'notes'             => $request->input('notes'),
            ]);

            foreach ($validated['items'] as $item) {
                $goodsReceipt->items()->create([
                    'purchase_order_item_id' => $item['purchase_order_item_id'],
                    'quantity_received'     => $item['quantity_received'],
                ]);
            }

            return $goodsReceipt;
        });

        return response()->json([
            'message' => 'Goods receipt created successfully.',
            'data'    => $grn->load('items')
        ], 201);
    }

    // =========================================================================
    // 4. THREE-WAY MATCHING
    // =========================================================================
    public function pendingMatches()
    {
        $pending = Invoice::with(['supplier', 'purchaseOrder.items', 'purchaseOrder.goodsReceipts.items'])
            ->where('match_status', 'pending')
            ->get();

        return response()->json($pending);
    }

    public function showMatch(Invoice $invoice)
    {
        return response()->json($invoice->load([
            'supplier',
            'purchaseOrder.items',
            'purchaseOrder.goodsReceipts.items'
        ]));
    }

    public function approveMatch(Invoice $invoice)
    {
        $invoice->update(['match_status' => 'matched']);

        return response()->json([
            'message' => 'Invoice 3-way match confirmed.',
            'data'    => $invoice
        ]);
    }

    // =========================================================================
    // 5. PAYMENTS
    // =========================================================================
    public function paymentIndex()
    {
        return response()->json(
            Payment::with('invoice.supplier')->latest()->paginate(15)
        );
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'amount_paid'    => 'required|numeric|min:0.01',
            'payment_date'   => 'required|date',
        ]);

        $payment = DB::transaction(function () use ($invoice, $validated) {
            $payment = Payment::create([
                'payment_reference' => 'PAY-' . strtoupper(uniqid()),
                'ap_invoice_id'     => $invoice->id,
                'amount_paid'       => $validated['amount_paid'],
                'payment_date'      => $validated['payment_date'],
                'payment_method'    => $validated['payment_method'],
                'status'            => 'processed',
            ]);

            $invoice->update(['payment_status' => 'paid']);

            return $payment;
        });

        return response()->json([
            'message' => 'Payment processed successfully.',
            'data'    => $payment->load('invoice')
        ]);
    }
}