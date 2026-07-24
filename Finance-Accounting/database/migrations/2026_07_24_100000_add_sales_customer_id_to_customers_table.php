<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ar_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_customer_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('ar_customers', function (Blueprint $table) {
            $table->dropColumn('sales_customer_id');
        });
    }
};