<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fa_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->string('maintenance_type');
            $table->string('technician')->nullable();
            $table->string('priority')->nullable(); // Low, Medium, High
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('scheduled'); // scheduled | completed
            $table->timestamps();

            $table->foreign('asset_id')
                ->references('asset_id')->on('fa_fixed_assets')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fa_maintenance_records');
    }
};
