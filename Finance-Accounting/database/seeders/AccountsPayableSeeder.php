<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountsPayableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ---------------- Suppliers ----------------
        $supplierIds = [];
        $suppliers = [
            ['name' => 'Global Supplies Co.', 'email' => 'global@supplies.com', 'phone' => '+63 2 8123 4501', 'payment_terms' => 'Net 30'],
            ['name' => 'Tech Solutions Inc.', 'email' => 'contact@techsolutions.com', 'phone' => '+63 2 8123 4502', 'payment_terms' => 'Net 30'],
            ['name' => 'Industrial Parts Corp.', 'email' => 'sales@industrialparts.com', 'phone' => '+63 2 8123 4503', 'payment_terms' => 'Net 45'],
            ['name' => 'Logistic Service LLC.', 'email' => 'info@logisticservice.com', 'phone' => '+63 2 8123 4504', 'payment_terms' => 'Net 15'],
            ['name' => 'Metro Office Supply', 'email' => 'orders@metrooffice.com', 'phone' => '+63 2 8123 4505', 'payment_terms' => 'Net 30'],
            ['name' => 'ABC Trading', 'email' => 'abctrading@gmail.com', 'phone' => '+63 2 8123 4506', 'payment_terms' => 'Net 30'],
            ['name' => 'Prime Industrial', 'email' => 'contact@primeindustrial.com', 'phone' => '+63 2 8123 4507', 'payment_terms' => 'Net 30'],
            ['name' => 'Northwind Traders', 'email' => 'sales@northwindtraders.com', 'phone' => '+63 2 8123 4508', 'payment_terms' => 'Net 30'],
            ['name' => 'Blue Ocean Corp.', 'email' => 'info@blueocean.com', 'phone' => '+63 2 8123 4509', 'payment_terms' => 'Net 30'],
            ['name' => 'Evergreen Supplies', 'email' => 'hello@evergreensupplies.com', 'phone' => '+63 2 8123 4510', 'payment_terms' => 'Net 15'],
        ];

        foreach ($suppliers as $supplier) {
            $supplierIds[$supplier['name']] = DB::table('suppliers')->insertGetId(array_merge($supplier, [
                'address' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // ---------------- Purchase orders ----------------
        $poIds = [];
        $purchaseOrders = [
            ['po_number' => 'PO-2024-001', 'supplier' => 'Global Supplies Co.', 'po_date' => '2026-05-10', 'total_amount' => 75000.00],
            ['po_number' => 'PO-2024-002', 'supplier' => 'Tech Solutions Inc.', 'po_date' => '2026-06-01', 'total_amount' => 42500.00],
            ['po_number' => 'PO-2024-003', 'supplier' => 'ABC Trading', 'po_date' => '2026-06-05', 'total_amount' => 85000.00],
            ['po_number' => 'PO-2024-004', 'supplier' => 'Prime Industrial', 'po_date' => '2026-05-20', 'total_amount' => 97000.00],
            ['po_number' => 'PO-2024-005', 'supplier' => 'Metro Office Supply', 'po_date' => '2026-06-10', 'total_amount' => 38200.00],
        ];

        foreach ($purchaseOrders as $po) {
            $poIds[$po['po_number']] = DB::table('purchase_orders')->insertGetId([
                'po_number' => $po['po_number'],
                'supplier_id' => $supplierIds[$po['supplier']],
                'po_date' => $po['po_date'],
                'total_amount' => $po['total_amount'],
                'status' => 'closed',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---------------- Goods receipts ----------------
        $goodsReceipts = [
            ['grn_number' => 'GRN-2024-021', 'po' => 'PO-2024-001', 'receipt_date' => '2026-05-12', 'total_amount' => 75000.00],
            ['grn_number' => 'GRN-2024-022', 'po' => 'PO-2024-002', 'receipt_date' => '2026-06-03', 'total_amount' => 42500.00],
            ['grn_number' => 'GRN-2024-023', 'po' => 'PO-2024-003', 'receipt_date' => '2026-06-07', 'total_amount' => 85000.00],
        ];

        foreach ($goodsReceipts as $grn) {
            DB::table('goods_receipts')->insert([
                'grn_number' => $grn['grn_number'],
                'purchase_order_id' => $poIds[$grn['po']],
                'receipt_date' => $grn['receipt_date'],
                'total_amount' => $grn['total_amount'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---------------- Invoices ----------------
        $invoiceIds = [];
        $invoices = [
            ['invoice_number' => 'INV-2024-00125', 'supplier' => 'Global Supplies Co.', 'po' => 'PO-2024-001', 'invoice_date' => '2026-05-15', 'due_date' => '2026-07-30', 'total_amount' => 75000.00, 'status' => 'approved', 'matched' => true],
            ['invoice_number' => 'INV-3002', 'supplier' => 'Tech Solutions Inc.', 'po' => 'PO-2024-002', 'invoice_date' => '2026-06-02', 'due_date' => '2026-07-20', 'total_amount' => 42500.00, 'status' => 'approved', 'matched' => true],
            ['invoice_number' => 'INV-3010', 'supplier' => 'Industrial Parts Corp.', 'po' => null, 'invoice_date' => '2026-07-05', 'due_date' => '2026-08-05', 'total_amount' => 35000.00, 'status' => 'pending_verification', 'matched' => false],
            ['invoice_number' => 'INV-3011', 'supplier' => 'Logistic Service LLC.', 'po' => null, 'invoice_date' => '2026-06-20', 'due_date' => '2026-07-15', 'total_amount' => 25000.00, 'status' => 'verified', 'matched' => false],
            ['invoice_number' => 'INV-3004', 'supplier' => 'Metro Office Supply', 'po' => 'PO-2024-005', 'invoice_date' => '2026-06-12', 'due_date' => '2026-07-18', 'total_amount' => 38200.00, 'status' => 'approved', 'matched' => true],
            ['invoice_number' => 'INV-3001', 'supplier' => 'ABC Trading', 'po' => 'PO-2024-003', 'invoice_date' => '2026-06-08', 'due_date' => '2026-07-25', 'total_amount' => 85000.00, 'status' => 'processing', 'matched' => true],
            ['invoice_number' => 'INV-3003', 'supplier' => 'Prime Industrial', 'po' => 'PO-2024-004', 'invoice_date' => '2026-05-22', 'due_date' => '2026-06-21', 'total_amount' => 97000.00, 'status' => 'overdue', 'matched' => true],
            ['invoice_number' => 'INV-3005', 'supplier' => 'Northwind Traders', 'po' => null, 'invoice_date' => '2026-06-25', 'due_date' => '2026-07-22', 'total_amount' => 61900.00, 'status' => 'pending_verification', 'matched' => false],
            ['invoice_number' => 'INV-3006', 'supplier' => 'Blue Ocean Corp.', 'po' => null, 'invoice_date' => '2026-06-24', 'due_date' => '2026-07-24', 'total_amount' => 73500.00, 'status' => 'scheduled', 'matched' => false],
            ['invoice_number' => 'INV-3007', 'supplier' => 'Evergreen Supplies', 'po' => null, 'invoice_date' => '2026-06-01', 'due_date' => '2026-07-23', 'total_amount' => 45000.00, 'status' => 'paid', 'matched' => false],
        ];

        foreach ($invoices as $inv) {
            $invoiceIds[$inv['invoice_number']] = DB::table('invoices')->insertGetId([
                'invoice_number' => $inv['invoice_number'],
                'supplier_id' => $supplierIds[$inv['supplier']],
                'purchase_order_id' => $inv['po'] ? $poIds[$inv['po']] : null,
                'invoice_date' => $inv['invoice_date'],
                'due_date' => $inv['due_date'],
                'payment_terms' => 'Net 30',
                'currency' => 'PHP',
                'department' => 'Finance Department',
                'supplier_reference' => null,
                'status' => $inv['status'],
                'subtotal' => $inv['total_amount'],
                'tax' => 0,
                'discount' => 0,
                'total_amount' => $inv['total_amount'],
                'remarks' => null,
                'verification_remarks' => null,
                'po_matched' => $inv['matched'],
                'grn_matched' => $inv['matched'],
                'invoice_matched' => $inv['matched'],
                'match_result' => $inv['matched'] ? 'APPROVED' : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---------------- Invoice items ----------------
        $items = [
            ['invoice' => 'INV-2024-00125', 'description' => 'Laptop', 'quantity' => 5, 'unit_price' => 15000.00, 'amount' => 75000.00],
            ['invoice' => 'INV-3002', 'description' => 'Office chairs', 'quantity' => 50, 'unit_price' => 850.00, 'amount' => 42500.00],
            ['invoice' => 'INV-3010', 'description' => 'Steel brackets', 'quantity' => 200, 'unit_price' => 175.00, 'amount' => 35000.00],
            ['invoice' => 'INV-3011', 'description' => 'Freight services - June', 'quantity' => 1, 'unit_price' => 25000.00, 'amount' => 25000.00],
            ['invoice' => 'INV-3004', 'description' => 'Printer paper (reams)', 'quantity' => 400, 'unit_price' => 55.50, 'amount' => 22200.00],
            ['invoice' => 'INV-3004', 'description' => 'Toner cartridges', 'quantity' => 20, 'unit_price' => 800.00, 'amount' => 16000.00],
            ['invoice' => 'INV-3001', 'description' => 'Raw materials - batch A', 'quantity' => 1, 'unit_price' => 85000.00, 'amount' => 85000.00],
            ['invoice' => 'INV-3003', 'description' => 'Industrial motors', 'quantity' => 10, 'unit_price' => 9700.00, 'amount' => 97000.00],
            ['invoice' => 'INV-3005', 'description' => 'Warehouse racking', 'quantity' => 1, 'unit_price' => 61900.00, 'amount' => 61900.00],
            ['invoice' => 'INV-3006', 'description' => 'Shipping containers', 'quantity' => 3, 'unit_price' => 24500.00, 'amount' => 73500.00],
            ['invoice' => 'INV-3007', 'description' => 'Landscaping services - Q2', 'quantity' => 1, 'unit_price' => 45000.00, 'amount' => 45000.00],
        ];

        foreach ($items as $item) {
            DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceIds[$item['invoice']],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['amount'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---------------- Invoice documents ----------------
        $documents = [
            ['invoice' => 'INV-2024-00125', 'type' => 'Supplier Invoice', 'file_name' => 'INV-2024-00125.pdf', 'size' => 1887436],
            ['invoice' => 'INV-2024-00125', 'type' => 'Purchase Order', 'file_name' => 'PO-2024-001.pdf', 'size' => 1003520],
            ['invoice' => 'INV-2024-00125', 'type' => 'Delivery Receipt', 'file_name' => 'GRN-2024-021.pdf', 'size' => 1153433],
        ];

        foreach ($documents as $doc) {
            DB::table('invoice_documents')->insert([
                'invoice_id' => $invoiceIds[$doc['invoice']],
                'document_type' => $doc['type'],
                'file_name' => $doc['file_name'],
                'file_path' => null,
                'file_size_bytes' => $doc['size'],
                'status' => 'uploaded',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---------------- Payments ----------------
        $payments = [
            ['invoice' => 'INV-2024-00125', 'reference' => 'PAY-2026-001', 'date' => '2026-06-15', 'amount' => 75000.00, 'method' => 'Bank Transfer', 'priority' => 'high', 'status' => 'approved'],
            ['invoice' => 'INV-3002', 'reference' => 'PAY-2026-002', 'date' => null, 'amount' => 42500.00, 'method' => 'Cheque', 'priority' => 'medium', 'status' => 'approved'],
            ['invoice' => 'INV-3001', 'reference' => 'PAY-2026-003', 'date' => null, 'amount' => 85000.00, 'method' => 'Bank Transfer', 'priority' => 'high', 'status' => 'processing'],
            ['invoice' => 'INV-3006', 'reference' => 'PAY-2026-004', 'date' => '2026-07-24', 'amount' => 73500.00, 'method' => 'Bank Transfer', 'priority' => 'high', 'status' => 'scheduled'],
            ['invoice' => 'INV-3007', 'reference' => 'PAY-2026-005', 'date' => '2026-07-01', 'amount' => 45000.00, 'method' => 'Cheque', 'priority' => 'low', 'status' => 'paid'],
        ];

        foreach ($payments as $pay) {
            DB::table('payments')->insert([
                'invoice_id' => $invoiceIds[$pay['invoice']],
                'reference_number' => $pay['reference'],
                'payment_date' => $pay['date'],
                'amount' => $pay['amount'],
                'payment_method' => $pay['method'],
                'priority' => $pay['priority'],
                'status' => $pay['status'],
                'remarks' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---------------- Activities ----------------
        $activities = [
            ['invoice' => 'INV-2024-00125', 'description' => 'Invoice INV-2024-00125 from Global Supplies Co. received.', 'type' => 'invoice_received', 'status' => 'done'],
            ['invoice' => 'INV-3002', 'description' => 'Payment of PHP 42,500 to Tech Solutions Inc. approved.', 'type' => 'payment_approved', 'status' => 'done'],
            ['invoice' => 'INV-3006', 'description' => 'Payment scheduled for INV-3006 on July 24, 2026.', 'type' => 'payment_scheduled', 'status' => 'scheduled'],
            ['invoice' => 'INV-3007', 'description' => 'Payment of PHP 45,000 to Evergreen Supplies, completed.', 'type' => 'payment_completed', 'status' => 'done'],
            ['invoice' => 'INV-2024-00125', 'description' => 'Three-way match completed for INV-2024-00125.', 'type' => 'three_way_match', 'status' => 'done'],
        ];

        foreach ($activities as $act) {
            DB::table('activities')->insert([
                'invoice_id' => $invoiceIds[$act['invoice']],
                'description' => $act['description'],
                'type' => $act['type'],
                'status' => $act['status'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
