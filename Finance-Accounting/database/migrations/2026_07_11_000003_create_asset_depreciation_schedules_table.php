<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fa_asset_depreciation_schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->foreignId('asset_id')
                  ->constrained('fa_fixed_assets', 'asset_id')
                  ->onDelete('cascade');
            $table->date('period_date'); // end of year/period
            $table->decimal('depreciation_expense', 14, 2);
            $table->decimal('accumulated_depreciation', 14, 2);
            $table->decimal('book_value', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fa_asset_depreciation_schedules');
    }
};
