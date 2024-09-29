<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (Product::count() > 0) {
            echo "Products already exist. No seeding required.";
            return;
        }

        // If no products exist, truncate the table and seed the data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            ['name' => 'Product A', 'type' => 1],
            ['name' => 'Product B', 'type' => 1],
            ['name' => 'Product C', 'type' => 2],
            ['name' => 'Product D', 'type' => 3],
            ['name' => 'Product E', 'type' => 3],
            ['name' => 'Product F', 'type' => 1],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        echo "Products table truncated and seeded.";
    }
}
