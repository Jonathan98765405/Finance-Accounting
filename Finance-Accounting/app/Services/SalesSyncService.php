<?php

namespace App\Services;

use App\Models\AccountReceivable\Customer;
use App\Models\AccountReceivable\Invoice;
use App\Models\AccountReceivable\InvoiceItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SalesSyncService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.sales.base_url'), '/');
        $this->token = config('services.sales.token');
    }

    /**
     * Run a full sync: customers first (invoices depend on them via
     * sales_customer_id), then invoices.
     */
    public function syncAll(): array
    {
        $customersSynced = $this->syncCustomers();
        $invoicesSynced = $this->syncInvoices();

        return [
            'customers' => $customersSynced,
            'invoices' => $invoicesSynced,
        ];
    }

    /**
     * Pull customers from the Sales API and upsert them locally.
     */
    public function syncCustomers(): int
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/customers");

        if (! $response->successful()) {
            Log::error('Sales sync: failed to fetch customers', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Failed to fetch customers from Sales API.');
        }

        $customers = $response->json();
        $count = 0;

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['sales_customer_id' => $customer['id']],
                [
                    'customer_name' => $customer['name'],
                    'company' => null,
                    'address' => $customer['address'] ?? null,
                    'email' => $customer['email'] ?? null,
                    'phone' => $customer['contact_no'] ?? null,
                ]
            );

            $count++;
        }

        return $count;
    }

    /**
     * Pull invoices (with their line items) from the Sales API and
     * upsert them locally. Assumes customers have already been synced.
     */
    public function syncInvoices(): int
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/sales-invoices");

        if (! $response->successful()) {
            Log::error('Sales sync: failed to fetch invoices', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Failed to fetch invoices from Sales API.');
        }

        $invoices = $response->json();
        $count = 0;

        foreach ($invoices as $invoice) {
            // Find the local customer that matches this sales customer.
            $localCustomer = Customer::where('sales_customer_id', $invoice['customer_id'])->first();

            if (! $localCustomer) {
                // Customer wasn't synced (shouldn't normally happen since we
                // sync customers first) — skip this invoice rather than fail
                // the whole batch.
                Log::warning('Sales sync: skipped invoice, no matching local customer', [
                    'sales_invoice_id' => $invoice['id'],
                    'sales_customer_id' => $invoice['customer_id'],
                ]);

                continue;
            }

            // If this invoice already has a payment applied on the Finance
            // side (Paid or Partial), don't let an incoming sync overwrite
            // that with possibly-stale data from the Sales API. Finance
            // status wins once a payment has been recorded.
            $existingInvoice = Invoice::where('sales_invoice_id', $invoice['id'])->first();

            if ($existingInvoice && in_array($existingInvoice->status, ['Paid', 'Partial'])) {

                if (($invoice['payment_status'] ?? null) !== $existingInvoice->status) {
                    Log::warning('Sales sync: skipped invoice with divergent payment status', [
                        'sales_invoice_id' => $invoice['id'],
                        'local_status' => $existingInvoice->status,
                        'incoming_sales_status' => $invoice['payment_status'] ?? null,
                    ]);
                }

                $count++;
                continue;
            }

            $totalAmount = (float) $invoice['total_amount'];
            $isPaid = ($invoice['payment_status'] ?? null) === 'Paid';
            $balance = $isPaid ? 0 : $totalAmount;

            $invoiceDate = $invoice['invoice_date'] ?? now();
            $dueDate = $invoice['due_date'] ?? $invoiceDate;

            $localInvoice = Invoice::updateOrCreate(
                ['sales_invoice_id' => $invoice['id']],
                [
                    'invoice_number' => $invoice['invoice_no'],
                    'customer_id' => $localCustomer->id,
                    'invoice_date' => Carbon::parse($invoiceDate),
                    'due_date' => Carbon::parse($dueDate),
                    'payment_terms' => $invoice['payment_terms'] ?? 'Net 30',
                    'subtotal' => $invoice['subtotal'] ?? 0,
                    'tax' => $invoice['tax_amount'] ?? 0,
                    'total' => $totalAmount,
                    'balance' => $balance,
                    'status' => $invoice['payment_status'] ?? 'Unpaid',
                    'notes' => 'Synced from Sales system',
                ]
            );

            // Sync line items (replace-all approach: clear then re-insert,
            // simplest way to stay consistent with the Sales side on every sync).
            if (! empty($invoice['items'])) {
                $localInvoice->items()->delete();

                foreach ($invoice['items'] as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $localInvoice->id,
                        'description' => $item['description'],
                        'quantity' => $item['qty'],
                        'unit_price' => $item['unit_price'],
                        'amount' => $item['amount'],
                    ]);
                }
            }

            $count++;
        }

        return $count;
    }

    /**
     * Tell the Sales system that an invoice has been fully paid on the
     * Finance side, so its status stays in sync.
     */
    public function markInvoicePaid(int $salesInvoiceId): bool
    {
        try {
            $response = Http::withToken($this->token)
                ->patch("{$this->baseUrl}/sales-invoices/{$salesInvoiceId}/mark-paid");

            if (! $response->successful()) {
                Log::error('Sales sync: failed to mark invoice as paid on Sales side', [
                    'sales_invoice_id' => $salesInvoiceId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;

        } catch (\Throwable $e) {
            Log::error('Sales sync: exception while marking invoice as paid', [
                'sales_invoice_id' => $salesInvoiceId,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}