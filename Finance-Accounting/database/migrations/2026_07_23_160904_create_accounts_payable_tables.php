<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. AP Suppliers
        if (!Schema::hasTable('ap_suppliers')) {
            Schema::create('ap_suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('tax_id')->nullable();
                $table->timestamps();
            });
        }

        // 2. AP Purchase Orders
        if (!Schema::hasTable('ap_purchase_orders')) {
            Schema::create('ap_purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('po_number')->unique();
                $table->foreignId('supplier_id')->constrained('ap_suppliers')->cascadeOnDelete();
                $table->date('order_date');
                $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed'])->default('draft');
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        // 3. AP Purchase Order Items
        if (!Schema::hasTable('ap_purchase_order_items')) {
            Schema::create('ap_purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained('ap_purchase_orders')->cascadeOnDelete();
                $table->string('item_description');
                $table->integer('quantity');
                $table->decimal('unit_price', 15, 2);
                $table->decimal('total_price', 15, 2);
                $table->timestamps();
            });
        }

        // 4. AP Goods Receipt Notes (GRN)
        if (!Schema::hasTable('ap_goods_receipts')) {
            Schema::create('ap_goods_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('grn_number')->unique();
                $table->foreignId('purchase_order_id')->constrained('ap_purchase_orders')->cascadeOnDelete();
                $table->date('received_date');
                $table->string('received_by');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 5. AP Goods Receipt Items
        if (!Schema::hasTable('ap_goods_receipt_items')) {
            Schema::create('ap_goods_receipt_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('goods_receipt_id')->constrained('ap_goods_receipts')->cascadeOnDelete();
                $table->foreignId('purchase_order_item_id')->constrained('ap_purchase_order_items')->cascadeOnDelete();
                $table->integer('quantity_received');
                $table->timestamps();
            });
        }

        // 6. AP Invoices
        if (!Schema::hasTable('ap_invoices')) {
            Schema::create('ap_invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('supplier_id')->constrained('ap_suppliers')->cascadeOnDelete();
                $table->foreignId('purchase_order_id')->nullable()->constrained('ap_purchase_orders')->nullOnDelete();
                $table->date('invoice_date');
                $table->date('due_date');
                $table->decimal('amount', 15, 2);
                $table->enum('match_status', ['pending', 'matched', 'discrepancy'])->default('pending');
                $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
                $table->timestamps();
            });
        }

        // 7. AP Payments
        if (!Schema::hasTable('ap_payments')) {
            Schema::create('ap_payments', function (Blueprint $table) {
                $table->id();
                $table->string('payment_reference')->unique();
                $table->foreignId('ap_invoice_id')->constrained('ap_invoices')->cascadeOnDelete();
                $table->decimal('amount_paid', 15, 2);
                $table->date('payment_date');
                $table->string('payment_method');
                $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ap_payments');
        Schema::dropIfExists('ap_invoices');
        Schema::dropIfExists('ap_goods_receipt_items');
        Schema::dropIfExists('ap_goods_receipts');
        Schema::dropIfExists('ap_purchase_order_items');
        Schema::dropIfExists('ap_purchase_orders');
        Schema::dropIfExists('ap_suppliers');
    }
};