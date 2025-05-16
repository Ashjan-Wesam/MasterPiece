<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;

class StoreSeeder extends Seeder
{
    public function run()
    {
        // Store::create([
        //     'store_name' => 'Crafty Corner',
        //     'description' => 'Beautiful handmade crafts.',
        //     'logo_url' => 'logo/crafty.png',
        //     'owner_id' => 100,
        // ]);

        // Store::create([
        //     'store_name' => 'Noura Knitting',
        //     'description' => 'Warm and cozy handmade clothing.',
        //     'logo_url' => 'logo/noura.png',
        //     'owner_id' => 101,
        // ]);

        // Store::create([
        //     'store_name' => 'Layla’s Art Shop',
        //     'description' => 'Unique artistic gifts and décor.',
        //     'logo_url' => 'logo/layla.png',
        //     'owner_id' => 102,
        // ]);

        $ownerSondos = User::where('email', 'SondostariqJ@gmail.com')->first();
        Store::create([
             'store_name' => 'BroBadge',
             'description' => 'Creative and stylish brooches & badges to match every outfit. Express yourself with BroBadge!',
             'logo_url' => 'logo/brobadge.png', 
             'owner_id' => $ownerSondos->id, 
        ]);

        $userBaraa = User::where('email', 'BaraaTayseerB@gmail.com')->first();
         Store::create([
        'store_name' => 'Artisan Vibes',
        'description' => 'Original paintings crafted using diverse artistic techniques including oil, sand, and acrylic.',
        'logo_url' => 'logo/artisan-vibes.png', 
        'owner_id' => $userBaraa->id,
        ]);

        
        $userRafeef = User::where('email', 'RafeefKhalidm@gmail.com')->first();
        Store::create([
       'store_name' => 'Rafeef’s Crochet Creations',
       'description' => 'Beautiful handmade crochet items crafted with care and creativity.',
       'logo_url' => 'logo/rafeef_crochet.png',  
       'owner_id' => $userRafeef->id,
        ]);

        $userManal = User::where('email', 'Manalkhalil2@gmail.com')->first();
        Store::create([
        'store_name' => 'Nature’s Glow',
        'description' => 'Natural beauty products made with pure and organic ingredients.',
        'logo_url' => 'logo/natures_glow.png',  
        'owner_id' => $userManal->id,
     ]);

        $userHala = User::where('email', 'halahassan2@gmail.com')->first();
        Store::create([
       'store_name' => 'Paws & Coziness',
      'description' => 'Handmade cozy clothes and gentle care products for your beloved pets.',
       'logo_url' => 'logo/paws_coziness.png', 
       'owner_id' => $userHala->id,
        ]);

        $userMaya = User::where('email', 'mayaomar2@gmail.com')->first();
         Store::create([
        'store_name' => 'Woolly Wonders',
        'description' => 'Hand-knitted wool clothing including hats, scarves, and warm accessories.',
        'logo_url' => 'logo/woolly_wonders.png', 
        'owner_id' => $userMaya->id,
        ]);

        $userMassah = User::where('email', 'Massah2@gmail.com')->first();
         Store::create([
         'store_name' => 'Elegant Gift Packages',
         'description' => 'Beautifully crafted gift packages and floral arrangements for all occasions.',
        'logo_url' => 'logo/elegant_gifts.png',
        'owner_id' => $userMassah->id,
        ]);

        $userBushra = User::where('email', 'Bushra2@gmail.com')->first();
         Store::create([
        'store_name' => 'Artisan Medals & Engravings',
        'description' => 'Custom wooden medals and personalized engraved designs.',
        'logo_url' => 'logo/artisan_medals.png',
        'owner_id' => $userBushra->id,
        ]);

        $userEnas = User::where('email', 'Enas2@gmail.com')->first();
         Store::create([
        'store_name' => 'Cap Print Studio',
        'description' => 'High-quality custom printed caps with unique designs.',
        'logo_url' => 'logo/cap_print_studio.png',
        'owner_id' => $userEnas->id,
        ]);

        $userRaghad = User::where('email', 'Raghad2@gmail.com')->first();
         Store::create([
        'store_name' => 'Cup Art Creations',
        'description' => 'Hand-painted mugs and cups with personalized artistic designs.',
        'logo_url' => 'logo/cup_art_creations.png', 
        'owner_id' => $userRaghad->id,
        ]);

        $userFatima = User::where('email', 'Fatima2@gmail.com')->first();
         Store::create([
         'store_name' => 'Eco Chic Boutique',
         'description' => 'Sustainable and stylish eco-friendly fashion and accessories.',
         'logo_url' => 'logo/eco_chic_boutique.png',
         'owner_id' => $userFatima->id,
        ]);

        $userMenna = User::where('email', 'Menna2@store.com')->first();
         Store::create([
        'store_name' => 'Artisan Candle Co.',
        'description' => 'Hand-poured scented candles with unique artistic designs.',
        'logo_url' => 'logo/artisan_candle_co.png',
        'owner_id' => $userMenna->id,
        ]);

        $userRania = User::where('email', 'Rania2@gmail.com')->first();
         Store::create([
        'store_name' => 'Vintage Vinyl Vault',
        'description' => 'Curated collection of rare and vintage vinyl records for music lovers.',
        'logo_url' => 'logo/vintage_vinyl_vault.png',
        'owner_id' => $userRania->id,
        ]);

        $userSamaher = User::where('email', 'Samaher2@gmail.com')->first();
         Store::create([
         'store_name' => 'Gourmet Spice Market',
    'description' => 'Exotic and handcrafted spice blends from around the world.',
    'logo_url' => 'logo/gourmet_spice_market.png', 
        'owner_id' => $userSamaher->id,
        ]);

        $userShaden = User::where('email', 'Shaden2@gmail.com')->first();
         Store::create([
        'store_name' => 'Handcrafted Paper Goods',
    'description' => 'Unique handmade stationery, journals, and greeting cards.',
    'logo_url' => 'logo/handcrafted_paper_goods.png',
        'owner_id' => $userShaden->id,
        ]);


    }
}
