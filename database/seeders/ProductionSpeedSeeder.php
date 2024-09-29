<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionSpeed;

class ProductionSpeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (ProductionSpeed::count() > 0) {
            echo "ProductionSpeed already exist. No seeding required.";
            return;
        }

        $productionSpeeds = [
            ['product_type' => 1, 'units_per_hour' => 715],
            ['product_type' => 2, 'units_per_hour' => 770],
            ['product_type' => 3, 'units_per_hour' => 1000],
        ];

        foreach ($productionSpeeds as $speed) {
            ProductionSpeed::create($speed);
        }
    }
}
