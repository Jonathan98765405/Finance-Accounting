<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fa_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->string('assigned_to', 150);
            $table->string('department', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->date('date_assigned');
            $table->string('cost_center', 50)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->timestamps();

            $table->foreign('asset_id')
                  ->references('asset_id')->on('fa_fixed_assets')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fa_assignments');
    }
};
