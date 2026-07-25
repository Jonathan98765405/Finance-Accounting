<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fa_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('type', 50);          // Purchase, Warranty, Manual, Maintenance, Depreciation, Insurance, Asset transfer form
            $table->string('description', 255)->nullable();
            $table->string('uploaded_by', 100)->nullable();
            $table->unsignedBigInteger('file_size'); // bytes
            $table->timestamps();

            $table->foreign('asset_id')
                  ->references('asset_id')->on('fa_fixed_assets')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fa_documents');
    }
};
