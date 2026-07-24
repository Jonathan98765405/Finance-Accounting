<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            // Reference to Procurement's own vendor id — different app/DB,
            // so this is intentionally NOT a local foreign key.
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('total_amount', 14, 2);
            $table->string('status')->default('pending_payment');
            $table->timestamp('ap_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
