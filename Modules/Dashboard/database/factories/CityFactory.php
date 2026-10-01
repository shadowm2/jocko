<?php

namespace Modules\Dashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Dashboard\Models\City::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

