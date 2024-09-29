<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChangeoverDelay;

class ChangeoverDelaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (ChangeoverDelay::count() > 0) {
            echo "ChangeoverDelay already exist. No seeding required.";
            return;
        }

        ChangeoverDelay::create([
            'delay_minutes' => 30,
        ]);
    }
}
