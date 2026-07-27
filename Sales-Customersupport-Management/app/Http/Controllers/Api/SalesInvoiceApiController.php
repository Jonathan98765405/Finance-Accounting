<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\SalesInvoiceResource;


class SalesInvoiceApiController extends Controller
{
   public function index()
{
    $invoices = SalesInvoice::with('customer', 'items')->get();

    return SalesInvoiceResource::collection($invoices);
}

public function show(SalesInvoice $salesInvoice)
{
    $salesInvoice->load('customer', 'items');

    return new SalesInvoiceResource($salesInvoice);
}

    

    /**
     * GET /api/v1/ar/invoices
     *
     * Listahan ng lahat ng unpaid AT partial invoices para sa Accounts
     * Receivable, may kasamang balance, days_overdue at aging_bucket
     * bawat isa. Gumagamit ng balance column kung meron (fallback sa
     * total_amount kung null pa ang balance ng lumang record).
     *
     * Optional query params:
     *   ?customer_id=5     i-filter sa isang customer
     *   ?bucket=31-60      i-filter sa isang aging bucket
     */
    public function unpaidInvoices(Request $request): JsonResponse
    {
        $query = SalesInvoice::with('customer')
            ->whereIn('payment_status', ['Unpaid', 'Partial'])
            ->orderBy('due_date');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        $invoices = $query->get();

        $data = $invoices->map(function (SalesInvoice $invoice) {
            $daysOverdue = $this->daysOverdue($invoice->due_date);
            $balance = $invoice->balance ?? $invoice->total_amount;

            return [
                'invoice_id'     => $invoice->id,
                'invoice_no'     => $invoice->invoice_no,
                'customer_id'    => $invoice->customer_id,
                'customer_name'  => $invoice->customer->name ?? null,
                'invoice_date'   => optional($invoice->invoice_date)->format('Y-m-d'),
                'due_date'       => optional($invoice->due_date)->format('Y-m-d'),
                'total_amount'   => (float) $invoice->total_amount,
                'balance'        => (float) $balance,
                'payment_status' => $invoice->payment_status,
                'days_overdue'   => $daysOverdue,
                'aging_bucket'   => $this->bucketFor($daysOverdue),
            ];
        });

        if ($request->filled('bucket')) {
            $data = $data->where('aging_bucket', $request->input('bucket'))->values();
        }

        return response()->json([
            'data' => $data,
            'meta' => [
                'count'            => $data->count(),
                'total_receivable' => round($data->sum('balance'), 2),
                'generated_at'     => now()->toDateTimeString(),
            ],
        ]);
    }

    /**
     * GET /api/v1/ar/aging-summary
     *
     * Buod ng lahat ng unpaid AT partial invoices, hinati sa standard
     * aging buckets, plus breakdown kung magkano ang utang ng bawat
     * customer. Gumagamit ng balance (hindi total_amount) para tumpak
     * ang totals kahit partial na ang ilang invoice.
     */
    public function agingSummary(): JsonResponse
    {
        $invoices = SalesInvoice::with('customer')
            ->whereIn('payment_status', ['Unpaid', 'Partial'])
            ->get();

        $buckets = [
            'current' => ['label' => 'Current (not yet due)', 'total' => 0, 'count' => 0],
            '1-30'    => ['label' => '1-30 days overdue',      'total' => 0, 'count' => 0],
            '31-60'   => ['label' => '31-60 days overdue',     'total' => 0, 'count' => 0],
            '61-90'   => ['label' => '61-90 days overdue',     'total' => 0, 'count' => 0],
            '90+'     => ['label' => 'Over 90 days overdue',   'total' => 0, 'count' => 0],
        ];

        $perCustomer = [];
        $totalReceivable = 0;

        foreach ($invoices as $invoice) {
            $daysOverdue = $this->daysOverdue($invoice->due_date);
            $bucket = $this->bucketFor($daysOverdue);
            $balance = (float) ($invoice->balance ?? $invoice->total_amount);

            $buckets[$bucket]['total'] += $balance;
            $buckets[$bucket]['count']++;
            $totalReceivable += $balance;

            $customerId = $invoice->customer_id;
            if (! isset($perCustomer[$customerId])) {
                $perCustomer[$customerId] = [
                    'customer_id'   => $customerId,
                    'customer_name' => $invoice->customer->name ?? null,
                    'total_owed'    => 0,
                    'invoice_count' => 0,
                ];
            }
            $perCustomer[$customerId]['total_owed'] += $balance;
            $perCustomer[$customerId]['invoice_count']++;
        }

        foreach ($buckets as $key => $bucket) {
            $buckets[$key]['total'] = round($bucket['total'], 2);
        }

        $perCustomer = collect($perCustomer)
            ->map(fn ($row) => [
                ...$row,
                'total_owed' => round($row['total_owed'], 2),
            ])
            ->sortByDesc('total_owed')
            ->values();

        return response()->json([
            'aging_buckets'    => $buckets,
            'by_customer'      => $perCustomer,
            'total_receivable' => round($totalReceivable, 2),
            'invoice_count'    => $invoices->count(),
            'generated_at'     => now()->toDateTimeString(),
        ]);
    }

    /**
     * Ilang days na lagpas sa due_date. Negative pag hindi pa due.
     */
    private function daysOverdue($dueDate): int
    {
        if (! $dueDate) {
            return 0;
        }

        return (int) now()->startOfDay()->diffInDays($dueDate, false) * -1;
    }

    /**
     * I-map ang days-overdue papunta sa standard aging bucket key.
     */
    private function bucketFor(int $daysOverdue): string
    {
        return match (true) {
            $daysOverdue <= 0 => 'current',
            $daysOverdue <= 30 => '1-30',
            $daysOverdue <= 60 => '31-60',
            $daysOverdue <= 90 => '61-90',
            default => '90+',
        };
    }

    /**
     * PATCH /api/v1/sales-invoices/{salesInvoice}/mark-paid
     *
     * Tinatawag ng Finance side (Accounts Receivable) kapag fully paid na
     * ang isang invoice. Ginagawang 0 rin ang balance dito.
     */
    public function markPaid(SalesInvoice $salesInvoice): JsonResponse
    {
        $salesInvoice->update([
            'payment_status' => 'Paid',
            'balance' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Invoice {$salesInvoice->invoice_no} marked as Paid.",
        ]);
    }

    /**
     * PATCH /api/v1/sales-invoices/{salesInvoice}/mark-partial
     *
     * Tinatawag ng Finance side (Accounts Receivable) kapag partial
     * payment lang ang na-record laban sa isang invoice. Hindi ito
     * ginagawang "Paid" dito sa Sales — makikita pa rin sa Sales na may
     * natitirang balance hangga't hindi ito fully paid sa Finance.
     *
     * Kailangan ng "balance" sa request body — ito yung natitirang utang
     * mula sa Finance side (ar_invoices.balance) pagkatapos ng partial
     * payment.
     */
    public function markPartial(SalesInvoice $salesInvoice, Request $request): JsonResponse
    {
        $request->validate([
            'balance' => 'required|numeric|min:0',
        ]);

        $salesInvoice->update([
            'payment_status' => 'Partial',
            'balance' => $request->input('balance'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Invoice {$salesInvoice->invoice_no} marked as Partial.",
        ]);
    }
}