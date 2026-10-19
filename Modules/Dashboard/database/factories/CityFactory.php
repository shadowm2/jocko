<?php

namespace Modules\Dashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Dashboard\Models\City;

/** @extends Factory<City> */
class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
