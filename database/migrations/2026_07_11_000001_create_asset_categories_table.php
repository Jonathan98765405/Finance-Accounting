<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fa_asset_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 100);
            $table->string('description', 255)->nullable();
            $table->integer('default_useful_life')->default(5); // in years
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fa_asset_categories');
    }
};
