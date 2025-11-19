<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Alisa Diaz',
            'price' => 395.00,
            'category_id' => 1,
            'image' => 'uploads/products/1692053146.jpg',
            'images' => ["uploads/products/1692053146_1.jpg", "uploads/products/1692053146_2.png"],
            'discount' => 47.00,
            'short_desc' => 'Ea perferendis sint.',
            'description' => 'Autem unde explicabo.s',
            'featured' => false,
            'status' => false,
            'stock' => 53,

        ]);
    }
}
