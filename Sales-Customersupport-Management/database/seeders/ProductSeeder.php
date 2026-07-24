<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Web Design Service',        'price' => 15000.00],
            ['name' => 'Logo Design',                'price' => 3500.00],
            ['name' => 'Monthly Hosting',             'price' => 800.00],
            ['name' => 'Domain Registration',         'price' => 650.00],
            ['name' => 'Consultation (per hour)',      'price' => 1200.00],
            ['name' => 'SEO Optimization',            'price' => 5000.00],
            ['name' => 'Social Media Management',     'price' => 7500.00],
            ['name' => 'Printed Business Cards (box)', 'price' => 450.00],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                ['price' => $product['price']]
            );
        }
    }
}