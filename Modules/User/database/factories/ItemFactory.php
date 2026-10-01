<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Item;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(95),
        ];
    }
}
