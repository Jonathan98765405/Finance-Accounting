<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            // Unique, human-readable invoice number (INV-YYYYMMDDHHMMSS)
            $table->string('invoice_no')->unique()->after('id');

            // Billing / scheduling
            $table->date('due_date')->nullable()->after('invoice_date');
            $table->string('payment_terms')->default('Net 30')->after('due_date');

            // Money breakdown (total_amount already exists — we keep it as the grand total)
            $table->decimal('subtotal', 12, 2)->default(0)->after('total_amount');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('tax_amount');

            // Optional freeform billing address/contact snapshot (not stored on Customer)
            $table->string('billing_address')->nullable()->after('customer_id');
            $table->string('billing_email')->nullable()->after('billing_address');
            $table->string('billing_phone')->nullable()->after('billing_email');
        });
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_no',
                'due_date',
                'payment_terms',
                'subtotal',
                'tax_amount',
                'discount_amount',
                'billing_address',
                'billing_email',
                'billing_phone',
            ]);
        });
    }
};