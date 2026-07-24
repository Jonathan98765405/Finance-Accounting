<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    private $defaults = [
        'marketing' => ['name' => 'Marketing', 'budget' => 1200000, 'actual' => 1024692],
        'operations' => ['name' => 'Operations', 'budget' => 2750000, 'actual' => 2654321],
        'sales' => ['name' => 'Sales', 'budget' => 2300000, 'actual' => 2123456],
        'technology' => ['name' => 'Technology', 'budget' => 1950000, 'actual' => 1876543],
        'human-resources' => ['name' => 'Human Resources', 'budget' => 1550000, 'actual' => 1543210],
        'finance' => ['name' => 'Finance', 'budget' => 3250000, 'actual' => 3123456]
    ];

    public function index() {
        $rows = DB::table('budgets')->get();
        
        if ($rows->isEmpty()) {
            return response()->json((object)$this->defaults);
        }

        $data = [];
        foreach($rows as $row) {
            $data[$row->category_key] = [
                'name' => $row->name, 
                'budget' => (float)$row->budget, 
                'actual' => (float)$row->actual
            ];
        }
        
        return response()->json((object)$data);
    }

   public function update(Request $request) {
        $request->validate([
            'records' => 'required|array',
        ]);

        $records = $request->input('records');

        if (DB::table('budgets')->count() === 0) {
            foreach ($this->defaults as $key => $values) {
                DB::table('budgets')->insert([
                    'category_key' => $key,
                    'name' => $values['name'],
                    'budget' => $values['budget'],
                    'actual' => $values['actual'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        foreach ($records as $key => $values) {
            
            $cleanBudget = isset($values['budget']) ? floatval(str_replace([',', '$'], '', $values['budget'])) : 0;
            $cleanActual = isset($values['actual']) ? floatval(str_replace([',', '$'], '', $values['actual'])) : 0;

            DB::table('budgets')->where('category_key', $key)->update([
                'budget' => $cleanBudget, 
                'actual' => $cleanActual,
                'updated_at' => now()
            ]);
        }
            
        return response()->json(['success' => true]);
    }
}