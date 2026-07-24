<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fa_fixed_assets', function (Blueprint $table) {
            $table->string('serial_number', 100)->nullable()->after('asset_name');
            $table->integer('warranty_years')->nullable()->after('useful_life_years');
            $table->text('description')->nullable()->after('location');
            $table->enum('condition', ['New', 'Good', 'Fair', 'Poor'])->default('Good')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('fa_fixed_assets', function (Blueprint $table) {
            $table->dropColumn(['serial_number', 'warranty_years', 'description', 'condition']);
        });
    }
};