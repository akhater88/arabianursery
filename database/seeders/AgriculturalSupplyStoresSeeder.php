<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgriculturalSupplyStoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agricultural_supply_stores')->insert([
            [
                'name' => 'Green Garden Supplies',
                'description' => 'Your one-stop shop for gardening tools and fertilizers.',
                'image_path' => 'images/stores/green_garden.png',
                'location' => DB::raw("POINT(35.924, 31.956)"),
                'address' => 'Amman, Jordan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fertile Fields Store',
                'description' => 'Providing high-quality seeds and pesticides.',
                'image_path' => 'images/stores/fertile_fields.png',
                'location' => DB::raw("POINT(36.204, 31.945)"),
                'address' => 'Irbid, Jordan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Harvest Supplies',
                'description' => 'Everything you need for a successful harvest season.',
                'image_path' => 'images/stores/harvest_supplies.png',
                'location' => DB::raw("POINT(35.850, 32.233)"),
                'address' => 'Jerash, Jordan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AgroTech Store',
                'description' => 'Innovative agricultural technology solutions.',
                'image_path' => 'images/stores/agrotech.png',
                'location' => DB::raw("POINT(36.107, 32.215)"),
                'address' => 'Zarqa, Jordan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nature’s Essentials',
                'description' => 'Organic supplies for sustainable farming.',
                'image_path' => 'images/stores/natures_essentials.png',
                'location' => DB::raw("POINT(36.094, 31.846)"),
                'address' => 'Madaba, Jordan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
