<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('fa_fixed_assets', function (Blueprint $table) {
            $table->id('asset_id');
            $table->string('asset_tag', 50)->unique(); // e.g. FA-2026-001
            $table->string('asset_name', 150);
            $table->foreignId('category_id')
                  ->constrained('fa_asset_categories', 'category_id')
                  ->onDelete('cascade');
            $table->date('acquisition_date');
            $table->decimal('acquisition_cost', 14, 2);
            $table->decimal('salvage_value', 14, 2)->default(0);
            $table->integer('useful_life_years');
            $table->enum('depreciation_method', ['straight_line', 'declining_balance'])
                  ->default('straight_line');
            $table->decimal('accumulated_depreciation', 14, 2)->default(0);
            $table->decimal('book_value', 14, 2)->default(0);
            $table->string('location', 100)->nullable();
            $table->enum('status', ['active', 'disposed', 'under_maintenance', 'fully_depreciated'])
                  ->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fa_fixed_assets');
    }
};
