<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Inventory\Models\Unit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->word();

        return [
            'name' => $name,
            'symbol' => $this->faker->randomLetter().($this->faker->boolean() ? $this->faker->randomLetter() : $this->faker->randomDigit()),
            'is_active' => $this->faker->boolean(80),
            'conversion_factor' => $this->faker->numberBetween(1, 10) * $this->faker->randomFloat(null, 0, 1),
        ];
    }
}
