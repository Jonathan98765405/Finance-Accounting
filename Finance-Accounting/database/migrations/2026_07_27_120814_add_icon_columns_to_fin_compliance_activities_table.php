<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fin_compliance_activities', function (Blueprint $table) {
            $table->string('icon')->default('activity')->after('title');
            $table->string('icon_color')->default('text-navy-600')->after('icon');
            $table->string('color')->default('text-slate-400')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('fin_compliance_activities', function (Blueprint $table) {
            $table->dropColumn(['icon', 'icon_color', 'color']);
        });
    }
};
