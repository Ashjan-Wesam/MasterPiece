<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('store_category')->insert([
            // Crafty Corner
            ['store_id' => 33, 'category_id' => 45], // Accessories
            ['store_id' => 33, 'category_id' => 46], // Handmade

            // Noura Knitting
            ['store_id' => 34, 'category_id' => 46], // Clothing
            ['store_id' => 34, 'category_id' => 45], // Handmade

            // Layla’s Art Shop
            ['store_id' => 35, 'category_id' => 45], // Handmade
            ['store_id' => 35, 'category_id' => 51], // Art
        ]);
    }
}
