<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SalesInvoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
{
    /** Flat 12% VAT — pull from config/settings if you already have a tax rate elsewhere. */
    private const TAX_RATE = 0.12;
    
public function index()
{
    $invoices = SalesInvoice::with('customer')
        ->latest()
        ->paginate(10);

    return view('sales-invoices.index', compact('invoices'));
}
    public function create()
{
    $customers = Customer::orderBy('name')->get();
    $products = Product::orderBy('name')->get();

    return view('sales-invoices.create', [
        'customers' => $customers,
        'products' => $products,
        'nextInvoiceNo' => $this->generateInvoiceNo(),
    ]);
}

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id'      => ['required', 'exists:customers,id'],
            'billing_address'  => ['nullable', 'string', 'max:255'],
            'billing_email'    => ['nullable', 'email', 'max:255'],
            'billing_phone'    => ['nullable', 'string', 'max:50'],
            'invoice_date'     => ['required', 'date'],
            'due_date'         => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'payment_terms'    => ['required', 'string', 'max:50'],
            'discount_amount'  => ['nullable', 'numeric', 'min:0'],
            'payment_status'   => ['required', 'in:Paid,Unpaid'],
            'items'                    => ['required', 'array', 'min:1'],
            'items.*.description'      => ['required', 'string', 'max:255'],
            'items.*.qty'              => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'       => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {
            // Recompute money server-side — never trust totals posted from the browser.
            $subtotal = collect($validated['items'])
                ->sum(fn ($item) => round($item['qty'] * $item['unit_price'], 2));

            $taxAmount = round($subtotal * self::TAX_RATE, 2);
            $discount  = round($validated['discount_amount'] ?? 0, 2);
            $total     = round($subtotal + $taxAmount - $discount, 2);

            $invoice = SalesInvoice::create([
                'invoice_no'       => $this->generateInvoiceNo(),
                'customer_id'      => $validated['customer_id'],
                'billing_address'  => $validated['billing_address'] ?? null,
                'billing_email'    => $validated['billing_email'] ?? null,
                'billing_phone'    => $validated['billing_phone'] ?? null,
                'invoice_date'     => $validated['invoice_date'],
                'due_date'         => $validated['due_date'] ?? null,
                'payment_terms'    => $validated['payment_terms'],
                'subtotal'         => $subtotal,
                'tax_amount'       => $taxAmount,
                'discount_amount'  => $discount,
                'total_amount'     => $total,
                'payment_status'   => $validated['payment_status'],
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'qty'         => $item['qty'],
                    'unit_price'  => $item['unit_price'],
                    'amount'      => round($item['qty'] * $item['unit_price'], 2),
                ]);
            }
        });

        return redirect()
            ->route('sales-invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Generates a unique invoice number. Timestamp-based with a random
     * suffix to avoid collisions when two invoices are created in the
     * same second.
     */
    private function generateInvoiceNo(): string
    {
        do {
            $candidate = 'INV-' . now()->format('YmdHis') . rand(10, 99);
        } while (SalesInvoice::where('invoice_no', $candidate)->exists());

        return $candidate;
    }

    
}