<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->decimal('balance', 12, 2)->nullable()->after('total_amount');
        });

        // Backfill: para sa mga existing invoices, i-set ang balance
        // base sa kasalukuyang payment_status nila.
        DB::table('sales_invoices')
            ->where('payment_status', 'Paid')
            ->update(['balance' => 0]);

        DB::table('sales_invoices')
            ->whereIn('payment_status', ['Unpaid', 'Partial'])
            ->update(['balance' => DB::raw('total_amount')]);
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};