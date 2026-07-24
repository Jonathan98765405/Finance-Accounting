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
        Schema::create('payments', function (Blueprint $table) {

    $table->id();

   $table->integer('invoice_id');

   $table->integer('customer_id');

    $table->date('payment_date');

    $table->string('payment_method');

    $table->string('reference_no')->nullable();

    $table->decimal('amount',12,2);

    $table->text('remarks')->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
