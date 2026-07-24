<?php

namespace App\Services;

use App\Models\AccountReceivable\Customer;
use App\Models\AccountReceivable\Invoice;
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
     * Pull invoices from the Sales API and upsert them locally.
     * Assumes customers have already been synced.
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

            $totalAmount = (float) $invoice['total_amount'];
            $isPaid = ($invoice['payment_status'] ?? null) === 'Paid';
            $balance = $isPaid ? 0 : $totalAmount;

            $invoiceDate = $invoice['invoice_date'] ?? now();
            $dueDate = $invoice['due_date'] ?? $invoiceDate;

            Invoice::updateOrCreate(
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

            $count++;
        }

        return $count;
    }
}