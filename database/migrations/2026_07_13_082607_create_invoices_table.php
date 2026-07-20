<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('invoices', function (Blueprint $table) {

        $table->id();

        $table->string('invoice_number')->unique();

        $table->foreignId('customer_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->date('invoice_date');
        $table->date('due_date');

        $table->string('payment_terms');

        $table->decimal('subtotal',12,2)->default(0);
        $table->decimal('tax',12,2)->default(0);
        $table->decimal('total',12,2)->default(0);
        $table->decimal('balance',12,2)->default(0);

       $table->enum('status', [
    'Paid',
    'Unpaid',
    'Partial',
    'Overdue'
])->default('Unpaid');

        $table->text('notes')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
