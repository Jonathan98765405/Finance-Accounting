<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountPayable\Supplier;
use App\Models\AccountPayable\PurchaseOrder;
use App\Models\AccountPayable\GoodsReceipt;
use App\Models\AccountPayable\Invoice;
use App\Models\AccountPayable\Payment;
use Carbon\Carbon;

class AccountsPayableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Sample Suppliers
        $supplierA = Supplier::create([
            'name'    => 'Acme Logistics & Supplies',
            'email'   => 'vendor@acmelogistics.com',
            'phone'   => '+1 (555) 019-2834',
            'address' => '123 Enterprise Way, Industrial Zone',
            'tax_id'  => 'VAT-987654321',
        ]);

        $supplierB = Supplier::create([
            'name'    => 'Global Tech Hardware',
            'email'   => 'sales@globaltech.io',
            'phone'   => '+1 (555) 482-9011',
            'address' => '450 Innovation Blvd, Suite 100',
            'tax_id'  => 'VAT-123456789',
        ]);

        // 2. Create Purchase Order 1 (Approved with Goods Receipt)
        $po1 = PurchaseOrder::create([
            'po_number'    => 'PO-2026-0001',
            'supplier_id'  => $supplierA->id,
            'order_date'   => Carbon::now()->subDays(10),
            'status'       => 'approved',
            'subtotal'     => 10000.00,
            'tax_amount'   => 1200.00,
            'total_amount' => 11200.00,
        ]);

        $item1 = $po1->items()->create([
            'item_description' => 'Industrial Storage Racks',
            'quantity'         => 10,
            'unit_price'       => 1000.00,
            'total_price'      => 10000.00,
        ]);

        // Create Goods Receipt for PO 1
        $grn1 = GoodsReceipt::create([
            'grn_number'        => 'GRN-2026-0001',
            'purchase_order_id' => $po1->id,
            'received_date'     => Carbon::now()->subDays(5),
            'received_by'       => 'Warehouse Manager',
            'notes'             => 'All items delivered in good condition.',
        ]);

        $grn1->items()->create([
            'purchase_order_item_id' => $item1->id,
            'quantity_received'     => 10,
        ]);

        // Create Pending Invoice for PO 1 (Ready for 3-Way Match)
        Invoice::create([
            'invoice_number'    => 'INV-ACME-8821',
            'supplier_id'       => $supplierA->id,
            'purchase_order_id' => $po1->id,
            'invoice_date'      => Carbon::now()->subDays(3),
            'due_date'          => Carbon::now()->addDays(27),
            'amount'            => 11200.00,
            'match_status'      => 'pending',
            'payment_status'    => 'unpaid',
        ]);

        // 3. Create Purchase Order 2 (Pending Approval)
        $po2 = PurchaseOrder::create([
            'po_number'    => 'PO-2026-0002',
            'supplier_id'  => $supplierB->id,
            'order_date'   => Carbon::now()->subDays(2),
            'status'       => 'pending',
            'subtotal'     => 5000.00,
            'tax_amount'   => 600.00,
            'total_amount' => 5600.00,
        ]);

        $po2->items()->create([
            'item_description' => 'Server Rack Switch 24-Port',
            'quantity'         => 2,
            'unit_price'       => 2500.00,
            'total_price'      => 5000.00,
        ]);

        // 4. Create Matched & Paid Invoice
        $po3 = PurchaseOrder::create([
            'po_number'    => 'PO-2026-0003',
            'supplier_id'  => $supplierB->id,
            'order_date'   => Carbon::now()->subDays(20),
            'status'       => 'completed',
            'subtotal'     => 2000.00,
            'tax_amount'   => 240.00,
            'total_amount' => 2240.00,
        ]);

        $paidInvoice = Invoice::create([
            'invoice_number'    => 'INV-GTH-1049',
            'supplier_id'       => $supplierB->id,
            'purchase_order_id' => $po3->id,
            'invoice_date'      => Carbon::now()->subDays(18),
            'due_date'          => Carbon::now()->subDays(2),
            'amount'            => 2240.00,
            'match_status'      => 'matched',
            'payment_status'    => 'paid',
        ]);

        Payment::create([
            'payment_reference' => 'PAY-2026-0001',
            'ap_invoice_id'     => $paidInvoice->id,
            'amount_paid'       => 2240.00,
            'payment_date'      => Carbon::now()->subDays(1),
            'payment_method'    => 'bank_transfer',
            'status'            => 'processed',
        ]);
    }
}