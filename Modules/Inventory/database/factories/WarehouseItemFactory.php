<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\WarehouseItem;

class WarehouseItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = WarehouseItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $quantity = $this->faker->randomNumber();
        $min_quantity = $this->faker->numberBetween(0, $quantity);
        $max_quantity = $this->faker->numberBetween($quantity);

        return [
            'quantity' => $quantity,
            'min_quantity' => $min_quantity,
            'max_quantity' => $max_quantity,
        ];
    }
}
