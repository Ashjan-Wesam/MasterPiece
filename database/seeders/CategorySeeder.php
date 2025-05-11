<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Handmade Accessories', 'description' => 'Crafted jewelry, keychains, and more'],
            ['name' => 'Traditional Clothing', 'description' => 'Hand-sewn dresses and traditional outfits'],
            ['name' => 'Home Decor', 'description' => 'Decorative items like candles, baskets, and paintings'],
            ['name' => 'Organic Products', 'description' => 'Homemade soaps, creams, and skincare items'],
            ['name' => 'Knitting & Crochet', 'description' => 'Wool products like scarves and baby clothes'],
            ['name' => 'Embroidery', 'description' => 'Palestinian and Arabic embroidered items'],
            // ['name' => 'Personalized Gifts', 'description' => 'Custom mugs, T-shirts, and boxes'],
            ['name' => 'Stationery & Art', 'description' => 'Hand-drawn notebooks, paintings, and crafts'],
            ['name' => 'Kids & Baby Products', 'description' => 'Toys, baby clothes, and accessories'],
            ['name' => 'Bags & Purses', 'description' => 'Handmade handbags, pouches, and wallets'],
            ['name' => 'Furniture & Woodwork', 'description' => 'Small handmade furniture and wood crafts'],
            ['name' => 'Natural Herbs & Teas', 'description' => 'Organic herbal mixes and dried teas'],
            ['name' => 'Pet Accessories', 'description' => 'Handmade pet beds, collars, and toys'],
            ['name' => 'Event Decorations', 'description' => 'Party supplies and custom event décor'],
            ['name' => 'Calligraphy & Typography', 'description' => 'Arabic calligraphy art and customized names'],
            ['name' => 'Kitchen Essentials', 'description' => 'Homemade aprons, holders, and spice blends'],
            ['name' => 'Seasonal Products', 'description' => 'Ramadan décor, holiday gifts, and themed items'],
        ]);
        
    }
}
