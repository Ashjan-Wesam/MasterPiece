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
          

             ['name' => 'Brooches', 'description' => 'Stylish and expressive pins for your outfit'],
             ['name' => 'Badges', 'description' => 'Custom and themed badges for personal use or gifts'],

             ['name' => 'Oil Paintings', 'description' => 'Artworks created using oil-based paints on canvas'],
             ['name' => 'Sand Art', 'description' => 'Intricate art pieces crafted using colored sand'],
             ['name' => 'Acrylic Paintings', 'description' => 'Modern paintings using fast-drying acrylic paints'],
             ['name' => 'Mixed Media Art', 'description' => 'Creative artworks combining multiple artistic techniques'],

             
            //  ['name' => 'Knitting & Crochet', 'description' => 'Beautiful wool and yarn creations including scarves, hats, and baby clothes.'],
            //  ['name' => 'Handmade Accessories', 'description' => 'Unique handcrafted jewelry and keychains to complement your style.'],
            //  ['name' => 'Kids & Baby Products', 'description' => 'Soft and cozy handmade items perfect for babies and kids.'],
            //  ['name' => 'Home Decor', 'description' => 'Crocheted decorative pieces to add warmth and charm to your home.'],


             
    ['name' => 'Natural Skincare', 'description' => 'Skincare products made from natural and organic ingredients.'],
    ['name' => 'Herbal Remedies', 'description' => 'Natural extracts and herbs for health and beauty enhancement.'],
    ['name' => 'Organic Body Care', 'description' => 'Natural oils, creams, and soaps for body care.'],
    ['name' => 'Eco-Friendly Beauty Tools', 'description' => 'Environmentally friendly and sustainable beauty tools.'],


    ['name' => 'Cozy Pet Clothes', 'description' => 'Soft and comfortable handmade clothing for pets.'],
    ['name' => 'Pet Beds & Blankets', 'description' => 'Warm and cozy beds and blankets made for your pets’ comfort.'],
    ['name' => 'Gentle Pet Care Products', 'description' => 'Natural and gentle grooming products for pets.'],
    // ['name' => 'Pet Accessories', 'description' => 'Handmade collars, leashes, and toys for your pets.'],

    ['name' => 'Wool Hats & Beanies', 'description' => 'Warm and stylish hand-knitted wool hats and beanies.'],
    ['name' => 'Scarves & Shawls', 'description' => 'Cozy wool scarves and shawls perfect for cold weather.'],
    ['name' => 'Wool Accessories', 'description' => 'Handmade wool gloves, mittens, and other warm accessories.'],
    
      ['name' => 'Gift Boxes & Hampers', 'description' => 'Beautifully arranged gift boxes and hampers for all occasions.'],
    ['name' => 'Floral Arrangements', 'description' => 'Fresh and dried flower arrangements, bouquets, and centerpieces.'],
    // ['name' => 'Personalized Gifts', 'description' => 'Customized gifts including cards, tags, and wrapping.'],


 ['name' => 'Wooden Medals', 'description' => 'Custom-made wooden medals for awards and recognition.'],
    ['name' => 'Engraved Gifts', 'description' => 'Personalized engraved items including plaques and trophies.'],
    ['name' => 'Custom Nameplates', 'description' => 'Unique engraved nameplates and signage for special occasions.'],

        ['name' => 'Custom Printed Caps', 'description' => 'High-quality caps printed with unique and personalized designs.'],
    ['name' => 'Sports Caps', 'description' => 'Durable and stylish caps suitable for sports and outdoor activities.'],
    ['name' => 'Fashion Caps', 'description' => 'Trendy caps designed for casual and everyday wear.'],

     ['name' => 'Hand-Painted Mugs', 'description' => 'Beautifully hand-painted mugs with artistic and custom designs.'],
    ['name' => 'Personalized Cups', 'description' => 'Custom cups designed to celebrate special moments and gifts.'],
    ['name' => 'Ceramic Art', 'description' => 'Unique ceramic art pieces including cups, plates, and decorative items.'],


['name' => 'Eco-Friendly Clothing', 'description' => 'Sustainable fashion pieces made from organic or recycled fabrics.'],
    ['name' => 'Reusable Accessories', 'description' => 'Chic and practical accessories like bags and pouches designed to reduce waste.'],
    ['name' => 'Natural Fiber Jewelry', 'description' => 'Handmade jewelry crafted using materials like hemp, bamboo, or recycled metals.'],

       ['name' => 'Scented Candles', 'description' => 'Aromatic candles made with essential oils for a calming ambiance.'],
    ['name' => 'Decorative Candles', 'description' => 'Artistic candles with beautiful shapes and colors for home décor.'],
    ['name' => 'Gift Sets', 'description' => 'Elegant candle gift boxes perfect for special occasions.'],


     ['name' => 'Classic Rock Vinyls', 'description' => 'Timeless rock albums from legendary bands.'],
    ['name' => 'Jazz & Blues Records', 'description' => 'Smooth and soulful records for laid-back vibes.'],
    ['name' => 'Rare Finds', 'description' => 'Limited edition or hard-to-find vinyl records.'],

        ['name' => 'Middle Eastern Spices', 'description' => 'Traditional spices like za’atar, sumac, and baharat blends.'],
    ['name' => 'Global Seasoning Blends', 'description' => 'Spice mixes inspired by Indian, Asian, and Latin cuisines.'],
    ['name' => 'Gift Spice Boxes', 'description' => 'Curated spice collections in decorative boxes—perfect for gifting.'],

    ['name' => 'Journals & Notebooks', 'description' => 'Beautifully bound handmade journals for writing or sketching.'],
    // ['name' => 'Greeting Cards', 'description' => 'Hand-painted or calligraphy-designed cards for every occasion.'],
    ['name' => 'Custom Paper Gifts', 'description' => 'Personalized paper crafts like bookmarks, planners, and invites.'],









        ]);
        
    }
}
