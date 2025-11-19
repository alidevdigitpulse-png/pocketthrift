<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition()
    {
        return [
            'country' => $this->faker->country,
            'code' => $this->faker->unique()->lexify('??'), // Two-letter country code
            'active' => true,
            'sort' => $this->faker->numberBetween(1, 100),
        ];
    }
}