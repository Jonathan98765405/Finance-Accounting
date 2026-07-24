<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'marketing' => ['name' => 'Marketing', 'budget' => 1200000, 'actual' => 1024692],
            'operations' => ['name' => 'Operations', 'budget' => 2750000, 'actual' => 2654321],
            'sales' => ['name' => 'Sales', 'budget' => 2300000, 'actual' => 2123456],
            'technology' => ['name' => 'Technology', 'budget' => 1950000, 'actual' => 1876543],
            'human-resources' => ['name' => 'Human Resources', 'budget' => 1550000, 'actual' => 1543210],
            'finance' => ['name' => 'Finance', 'budget' => 3250000, 'actual' => 3123456]
        ];

        foreach ($defaults as $key => $values) {
            DB::table('budgets')->updateOrInsert(
                ['category_key' => $key], // Checks if this key already exists
                [
                    'name' => $values['name'],
                    'budget' => $values['budget'],
                    'actual' => $values['actual'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}