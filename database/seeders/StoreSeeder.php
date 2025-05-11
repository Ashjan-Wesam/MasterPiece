<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    public function run()
    {
        Store::create([
            'store_name' => 'Crafty Corner',
            'description' => 'Beautiful handmade crafts.',
            'logo_url' => 'logos/crafty.png',
            'owner_id' => 100,
        ]);

        Store::create([
            'store_name' => 'Noura Knitting',
            'description' => 'Warm and cozy handmade clothing.',
            'logo_url' => 'logos/noura.png',
            'owner_id' => 101,
        ]);

        Store::create([
            'store_name' => 'Layla’s Art Shop',
            'description' => 'Unique artistic gifts and décor.',
            'logo_url' => 'logos/layla.png',
            'owner_id' => 102,
        ]);
    }
}
