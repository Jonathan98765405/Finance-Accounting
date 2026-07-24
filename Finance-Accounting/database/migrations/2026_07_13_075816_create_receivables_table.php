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
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_no')->unique();
            $table->string('customer_name');

            $table->date('invoice_date');
            $table->date('due_date');

            $table->decimal('total_amount', 12, 2);
            $table->decimal('balance', 12, 2);


$table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};