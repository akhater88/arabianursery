<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'store_id' => 1,
            'name' => 'Premium Fertilizer',
            'image' => 'products/fertilizer.png',
            'description' => 'High-quality fertilizer for better crop yield.',
            'price' => 19.99,
        ]);

        Product::create([
            'store_id' => 1,
            'name' => 'Organic Pesticide',
            'image' => 'products/pesticide.png',
            'description' => 'Eco-friendly pesticide for safe pest control.',
            'price' => 15.49,
        ]);

        // Add more products here...
    }
}
