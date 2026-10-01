<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Warehouse;

class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
