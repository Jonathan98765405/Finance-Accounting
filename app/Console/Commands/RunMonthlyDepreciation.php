<?php

namespace App\Console\Commands;

use App\Models\FixedAssets\FixedAsset;
use App\Services\GeneralLedgerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunMonthlyDepreciation extends Command
{
    protected $signature = 'depreciation:run {--date=}';
    protected $description = 'Calculate and post monthly depreciation for all active fixed assets';

    public function handle(GeneralLedgerService $gl)
    {
        $periodDate = $this->option('date') ?? now()->format('Y-m-d');

        $assets = FixedAsset::where('status', 'active')->get();

        if ($assets->isEmpty()) {
            $this->info('No active assets to depreciate.');
            return;
        }

        foreach ($assets as $asset) {
            $depreciableBase = $asset->acquisition_cost - $asset->salvage_value;
            $monthlyExpense = round($depreciableBase / ($asset->useful_life_years * 12), 2);

            $newAccumulated = $asset->accumulated_depreciation + $monthlyExpense;
            $newBookValue = $asset->acquisition_cost - $newAccumulated;

            // Cap at salvage value once fully depreciated
            if ($newBookValue <= $asset->salvage_value) {
                $monthlyExpense = round($asset->book_value - $asset->salvage_value, 2);
                if ($monthlyExpense <= 0) {
                    continue;
                }
                $newAccumulated = $asset->accumulated_depreciation + $monthlyExpense;
                $newBookValue = $asset->salvage_value;
            }

            DB::transaction(function () use ($asset, $monthlyExpense, $newAccumulated, $newBookValue, $periodDate, $gl) {
                $asset->depreciationSchedules()->create([
                    'period_date'              => $periodDate,
                    'depreciation_expense'     => $monthlyExpense,
                    'accumulated_depreciation' => $newAccumulated,
                    'book_value'               => $newBookValue,
                ]);

                $asset->update([
                    'accumulated_depreciation' => $newAccumulated,
                    'book_value'               => $newBookValue,
                    'status' => $newBookValue <= $asset->salvage_value ? 'fully_depreciated' : 'active',
                ]);

                $gl->postDepreciation($asset, $monthlyExpense, $periodDate);
            });

            $this->info("Posted depreciation for {$asset->asset_tag}: {$monthlyExpense}");
        }

        $this->info('Depreciation run complete.');
    }
}