<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create([
            'country' => 'United States',
            'code' => 'us',
            'sort' => 1,
            'active' => true,
        ]);

        Region::create([
            'country' => 'United Kingdom',
            'code' => 'uk',
            'sort' => 2,
            'active' => true,
        ]);

        Region::create([
            'country' => 'Australia',
            'code' => 'au',
            'sort' => 3,
            'active' => true,
        ]);
    }
}
