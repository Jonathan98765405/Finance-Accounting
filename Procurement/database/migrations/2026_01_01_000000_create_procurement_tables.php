<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vendors / Suppliers
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('payment_terms')->default('NET30'); // e.g. NET30, NET60, Due on Receipt
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Purchase Requisitions
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Requester
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'ordered'])->default('draft');
            $table->text('purpose')->nullable();
            $table->timestamps();
        });

        // Requisition Items
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });

        // Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            $table->foreignId('requisition_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'sent_to_ap', 'completed'])->default('draft');
            $table->timestamp('ap_synced_at')->nullable();
            $table->timestamps();
        });

        // Purchase Order Items
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });

        // Approval Logs (Polymorphic: supports both Requisitions and POs)
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->morphs('approvable');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Approver
            $table->enum('status', ['approved', 'rejected']);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('requisition_items');
        Schema::dropIfExists('requisitions');
        Schema::dropIfExists('vendors');
    }
};