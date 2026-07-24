<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->enum('status', [
                'Paid',
                'Unpaid',
                'Partial',
                'Overdue'
            ])->default('Unpaid')->change();

        });
    }


    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->enum('status', [
                'Paid',
                'Unpaid',
                'Overdue'
            ])->default('Unpaid')->change();

        });
    }
};