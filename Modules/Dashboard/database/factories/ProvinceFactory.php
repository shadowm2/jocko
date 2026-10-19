<?php

namespace Modules\Dashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Dashboard\Models\Province;

/** @extends Factory<Province> */
class ProvinceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Province::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
