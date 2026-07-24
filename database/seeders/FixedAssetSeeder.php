<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\AssetDepreciationSchedule;
use App\Models\FixedAsset;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FixedAssetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = [
            ['category_name' => 'Office Equipment', 'description' => 'Computers, printers, aircon, etc.', 'default_useful_life' => 5],
            ['category_name' => 'Furniture & Fixtures', 'description' => 'Desks, chairs, cabinets', 'default_useful_life' => 10],
            ['category_name' => 'Vehicles', 'description' => 'Company-owned vehicles', 'default_useful_life' => 8],
            ['category_name' => 'Buildings', 'description' => 'Office buildings and warehouses', 'default_useful_life' => 25],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $created = AssetCategory::create($cat);
            $categoryIds[$cat['category_name']] = $created->category_id;
        }

        // 2. Fixed Assets (dummy data)
        $assets = [
            ['asset_tag' => 'FA-2023-001', 'asset_name' => 'Dell OptiPlex Desktop PC', 'category' => 'Office Equipment', 'acquisition_date' => '2023-01-15', 'acquisition_cost' => 45000, 'salvage_value' => 3000, 'useful_life_years' => 5, 'location' => 'Accounting Office'],
            ['asset_tag' => 'FA-2023-002', 'asset_name' => 'HP LaserJet Printer', 'category' => 'Office Equipment', 'acquisition_date' => '2023-03-10', 'acquisition_cost' => 15000, 'salvage_value' => 1000, 'useful_life_years' => 5, 'location' => 'Admin Office'],
            ['asset_tag' => 'FA-2022-003', 'asset_name' => 'Split-Type Air Conditioner', 'category' => 'Office Equipment', 'acquisition_date' => '2022-06-20', 'acquisition_cost' => 38000, 'salvage_value' => 2000, 'useful_life_years' => 5, 'location' => 'Conference Room'],
            ['asset_tag' => 'FA-2021-004', 'asset_name' => 'Executive Office Desk', 'category' => 'Furniture & Fixtures', 'acquisition_date' => '2021-05-05', 'acquisition_cost' => 22000, 'salvage_value' => 1500, 'useful_life_years' => 10, 'location' => 'Manager Office'],
            ['asset_tag' => 'FA-2021-005', 'asset_name' => 'Ergonomic Office Chairs (Set of 10)', 'category' => 'Furniture & Fixtures', 'acquisition_date' => '2021-05-05', 'acquisition_cost' => 55000, 'salvage_value' => 3000, 'useful_life_years' => 10, 'location' => 'Open Workspace'],
            ['asset_tag' => 'FA-2020-006', 'asset_name' => 'Filing Cabinet (Steel, 4-Drawer)', 'category' => 'Furniture & Fixtures', 'acquisition_date' => '2020-09-12', 'acquisition_cost' => 12000, 'salvage_value' => 500, 'useful_life_years' => 10, 'location' => 'Records Room'],
            ['asset_tag' => 'FA-2022-007', 'asset_name' => 'Toyota Hiace Delivery Van', 'category' => 'Vehicles', 'acquisition_date' => '2022-02-18', 'acquisition_cost' => 1450000, 'salvage_value' => 150000, 'useful_life_years' => 8, 'location' => 'Motor Pool'],
            ['asset_tag' => 'FA-2019-008', 'asset_name' => 'Mitsubishi Mirage Company Car', 'category' => 'Vehicles', 'acquisition_date' => '2019-11-30', 'acquisition_cost' => 720000, 'salvage_value' => 80000, 'useful_life_years' => 8, 'location' => 'Motor Pool'],
            ['asset_tag' => 'FA-2018-009', 'asset_name' => 'Main Office Building', 'category' => 'Buildings', 'acquisition_date' => '2018-01-01', 'acquisition_cost' => 8500000, 'salvage_value' => 1000000, 'useful_life_years' => 25, 'location' => 'Head Office'],
            ['asset_tag' => 'FA-2024-010', 'asset_name' => 'Warehouse Storage Facility', 'category' => 'Buildings', 'acquisition_date' => '2024-04-22', 'acquisition_cost' => 3200000, 'salvage_value' => 400000, 'useful_life_years' => 25, 'location' => 'Logistics Site'],
        ];

        foreach ($assets as $data) {
            $categoryId = $categoryIds[$data['category']];
            $acqDate = Carbon::parse($data['acquisition_date']);
            $depreciableAmount = $data['acquisition_cost'] - $data['salvage_value'];
            $annualDepreciation = round($depreciableAmount / $data['useful_life_years'], 2);

            // Compute how many full years have passed since acquisition until today
            $yearsElapsed = min(
                $acqDate->diffInYears(Carbon::now()),
                $data['useful_life_years']
            );

            $accumulatedDepreciation = round($annualDepreciation * $yearsElapsed, 2);
            $bookValue = $data['acquisition_cost'] - $accumulatedDepreciation;

            $status = 'active';
            if ($yearsElapsed >= $data['useful_life_years']) {
                $status = 'fully_depreciated';
            }

            $asset = FixedAsset::create([
                'asset_tag' => $data['asset_tag'],
                'asset_name' => $data['asset_name'],
                'category_id' => $categoryId,
                'acquisition_date' => $data['acquisition_date'],
                'acquisition_cost' => $data['acquisition_cost'],
                'salvage_value' => $data['salvage_value'],
                'useful_life_years' => $data['useful_life_years'],
                'depreciation_method' => 'straight_line',
                'accumulated_depreciation' => $accumulatedDepreciation,
                'book_value' => $bookValue,
                'location' => $data['location'],
                'status' => $status,
            ]);

            // 3. Generate yearly depreciation schedule rows
            $runningAccum = 0;
            for ($i = 1; $i <= $yearsElapsed; $i++) {
                $runningAccum += $annualDepreciation;
                $periodDate = $acqDate->copy()->addYears($i);

                AssetDepreciationSchedule::create([
                    'asset_id' => $asset->asset_id,
                    'period_date' => $periodDate->format('Y-m-d'),
                    'depreciation_expense' => $annualDepreciation,
                    'accumulated_depreciation' => round($runningAccum, 2),
                    'book_value' => round($data['acquisition_cost'] - $runningAccum, 2),
                ]);
            }
        }
    }
}
