<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Product::insert([
            // Crafty Corner - Accessories
            [
                'name' => 'Beaded Earrings',
                'description' => 'Elegant handmade beaded earrings. || custom colors available',
                'price' => 7.00,
                'stock_quantity' => 25,
                'store_id' => 33,
                'category_id' => 45,
                'image_url' => 'products/earrings.jpg',
                'request' => 'yes',
            ],
            [
                'name' => 'Woven Bracelet',
                'description' => 'Stylish handmade woven bracelet.',
                'price' => 5.50,
                'stock_quantity' => 30,
                'store_id' => 33,
                'category_id' => 46,
                'image_url' => 'products/bracelet.jpg',
                'request' => 'no',
            ],

            // Crafty Corner - Handmade
            [
                'name' => 'Clay Pottery Mug',
                'description' => 'Rustic handmade clay mug. || name engraving available',
                'price' => 10.00,
                'stock_quantity' => 15,
                'store_id' => 33,
                'category_id' => 45,
                'image_url' => 'products/mug.jpg',
                'request' => 'yes',
            ],
            [
                'name' => 'Macrame Keychain',
                'description' => 'Boho style handmade macrame keychain.|| color choice available',
                'price' => 4.00,
                'stock_quantity' => 40,
                'store_id' => 33,
                'category_id' => 46,
                'image_url' => 'products/keychain.jpg',
                'request' => 'yes',
            ],

            // Noura Knitting - Clothing
            [
                'name' => 'Knitted Sweater',
                'description' => 'Warm handmade sweater. || size options available',
                'price' => 25.00,
                'stock_quantity' => 10,
                'store_id' => 34,
                'category_id' => 46,
                'image_url' => 'products/sweater.jpg',
                'request' => 'yes',
            ],
            [
                'name' => 'Wool Hat',
                'description' => 'Soft wool hat for winter. || custom color',
                'price' => 8.00,
                'stock_quantity' => 20,
                'store_id' => 34,
                'category_id' => 46,
                'image_url' => 'products/hat.jpg',
                'request' => 'yes',
            ],

            // Noura Knitting - Handmade
            [
                'name' => 'Knitted Bag',
                'description' => 'Handmade stylish bag. || Add Your name tag',
                'price' => 15.00,
                'stock_quantity' => 12,
                'store_id' => 34,
                'category_id' => 45,
                'image_url' => 'products/bag.jpg',
                'request' => 'yes',
            ],
            [
                'name' => 'Knitted Doll',
                'description' => 'Cute and soft handmade doll.',
                'price' => 9.00,
                'stock_quantity' => 18,
                'store_id' => 34,
                'category_id' => 45,
                'image_url' => 'products/doll.jpg',
                'request' => 'no',
            ],

            // Layla’s Art Shop - Handmade
            [
                'name' => 'Embroidered Pouch',
                'description' => 'Hand-sewn pouch with embroidery.',
                'price' => 6.00,
                'stock_quantity' => 22,
                'store_id' => 35,
                'category_id' => 45,
                'image_url' => 'products/pouch.jpg',
                'request' => 'no',
            ],
            [
                'name' => 'Decorative Basket',
                'description' => 'Handwoven decorative basket. || color variation',
                'price' => 13.00,
                'stock_quantity' => 16,
                'store_id' => 35,
                'category_id' => 45,
                'image_url' => 'products/basket.jpg',
                'request' => 'yes',
            ],

            // Layla’s Art Shop - Art
            [
                'name' => 'Canvas Painting',
                'description' => 'Original acrylic painting on canvas.||custom scene available',
                'price' => 30.00,
                'stock_quantity' => 5,
                'store_id' => 35,
                'category_id' => 51,
                'image_url' => 'products/painting.jpg',
                'request' => 'yes',
            ],
            [
                'name' => 'Mini Sculpture',
                'description' => 'Hand-carved mini wooden sculpture.',
                'price' => 18.00,
                'stock_quantity' => 8,
                'store_id' => 35,
                'category_id' => 51,
                'image_url' => 'products/sculpture.jpg',
                'request' => 'no',
            ],
        ]);
    }
}
