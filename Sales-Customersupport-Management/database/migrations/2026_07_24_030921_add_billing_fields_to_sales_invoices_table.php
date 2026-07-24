<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {

            $table->string('billing_address')->nullable()->after('customer_id');
            $table->string('billing_email')->nullable()->after('billing_address');
            $table->string('billing_phone')->nullable()->after('billing_email');

        });
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {

            $table->dropColumn([
                'billing_address',
                'billing_email',
                'billing_phone'
            ]);

        });
    }
};
