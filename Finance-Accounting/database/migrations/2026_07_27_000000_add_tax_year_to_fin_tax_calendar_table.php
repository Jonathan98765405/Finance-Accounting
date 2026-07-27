<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds fin_tax_calendar.tax_year.
 *
 * Why this is needed: a filing's due_date is "1 month after the last
 * invoice that incurred it", so a filing that belongs to (e.g.) December
 * 2026 can have a due_date in January 2027. Everywhere we need "give me
 * this table's rows for tax year X" (Filed YTD, Pending Filings, the
 * calendar sync itself) was previously filtering on YEAR(due_date),
 * which silently missed those rolled-over rows. tax_year stores the
 * actual tax period explicitly so that filtering is correct regardless
 * of which month the deadline falls in.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fin_tax_calendar', function (Blueprint $table) {
            $table->unsignedSmallInteger('tax_year')->nullable()->after('due_date')->index();
        });

        // Backfill existing rows so old data doesn't silently disappear
        // from year-filtered queries: default to the year of the due_date.
        DB::table('fin_tax_calendar')
            ->whereNull('tax_year')
            ->update(['tax_year' => DB::raw('YEAR(due_date)')]);
    }

    public function down(): void
    {
        Schema::table('fin_tax_calendar', function (Blueprint $table) {
            $table->dropColumn('tax_year');
        });
    }
};
