<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fa_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action', 30);        // created, updated, deleted, disposed
            $table->string('description', 255);
            $table->string('performed_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fa_activity_logs');
    }
};