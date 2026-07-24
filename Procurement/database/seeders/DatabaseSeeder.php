<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Approval;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Sample Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $approver = User::create([
            'name' => 'Sarah Manager',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
        ]);

        $requester = User::create([
            'name' => 'John Developer',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Sample Vendors
        $vendor1 = Vendor::create([
            'name' => 'Acme Tech Solutions',
            'code' => 'VEND-ACME',
            'email' => 'sales@acmetech.com',
            'phone' => '+1 (555) 019-2834',
            'address' => '100 Innovation Way, Silicon Valley, CA',
            'payment_terms' => 'NET30',
            'status' => 'active',
        ]);

        $vendor2 = Vendor::create([
            'name' => 'Global Office Supplies',
            'code' => 'VEND-GLOBL',
            'email' => 'orders@globaloffice.com',
            'phone' => '+1 (555) 014-9921',
            'address' => '45 Logistics Blvd, Chicago, IL',
            'payment_terms' => 'NET60',
            'status' => 'active',
        ]);

        $vendor3 = Vendor::create([
            'name' => 'Apex Industrial Gear',
            'code' => 'VEND-APEX',
            'email' => 'contact@apexgear.io',
            'phone' => '+1 (555) 018-4410',
            'address' => '882 Heavy Ind Rd, Houston, TX',
            'payment_terms' => 'Due on Receipt',
            'status' => 'active',
        ]);

        // 3. Create Sample Requisition 1 (Pending Approval)
        $req1 = Requisition::create([
            'requisition_number' => 'PR-2026-001',
            'user_id' => $requester->id,
            'purpose' => 'Developer Workstation Refresh Q1',
            'status' => 'pending_approval',
            'total_amount' => 4200.00,
        ]);

        RequisitionItem::create([
            'requisition_id' => $req1->id,
            'description' => 'MacBook Pro 16" (M3 Max, 36GB RAM)',
            'quantity' => 1,
            'unit_price' => 3400.00,
            'total_price' => 3400.00,
        ]);

        RequisitionItem::create([
            'requisition_id' => $req1->id,
            'description' => '4K USB-C Monitor 27"',
            'quantity' => 2,
            'unit_price' => 400.00,
            'total_price' => 800.00,
        ]);

        // 4. Create Sample Requisition 2 (Approved & Converted to PO)
        $req2 = Requisition::create([
            'requisition_number' => 'PR-2026-002',
            'user_id' => $requester->id,
            'purpose' => 'Office Ergonomic Supplies',
            'status' => 'ordered',
            'total_amount' => 1250.00,
        ]);

        RequisitionItem::create([
            'requisition_id' => $req2->id,
            'description' => 'Ergonomic Task Chairs',
            'quantity' => 5,
            'unit_price' => 250.00,
            'total_price' => 1250.00,
        ]);

        // Approval record for Requisition 2
        Approval::create([
            'approvable_type' => Requisition::class,
            'approvable_id' => $req2->id,
            'user_id' => $approver->id,
            'status' => 'approved',
            'comment' => 'Approved budget for office ergonomics.',
        ]);

        // 5. Create Sample Purchase Orders
        // PO 1 (Derived from Requisition 2, Approved & Synced to AP)
        $po1 = PurchaseOrder::create([
            'po_number' => 'PO-2026-001',
            'vendor_id' => $vendor2->id,
            'requisition_id' => $req2->id,
            'created_by' => $admin->id,
            'total_amount' => 1250.00,
            'status' => 'sent_to_ap',
            'ap_synced_at' => now(),
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po1->id,
            'description' => 'Ergonomic Task Chairs',
            'quantity' => 5,
            'unit_price' => 250.00,
            'total_price' => 1250.00,
        ]);

        // PO 2 (Direct PO, Approved)
        $po2 = PurchaseOrder::create([
            'po_number' => 'PO-2026-002',
            'vendor_id' => $vendor1->id,
            'requisition_id' => null,
            'created_by' => $admin->id,
            'total_amount' => 850.00,
            'status' => 'approved',
            'ap_synced_at' => null,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po2->id,
            'description' => 'Annual Cloud Hosting Credit Package',
            'quantity' => 1,
            'unit_price' => 850.00,
            'total_price' => 850.00,
        ]);

        // Approval record for PO 2
        Approval::create([
            'approvable_type' => PurchaseOrder::class,
            'approvable_id' => $po2->id,
            'user_id' => $approver->id,
            'status' => 'approved',
            'comment' => 'Approved recurring infrastructure expense.',
        ]);
    }
}