<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Item;

class InventoryItemFactory extends Factory
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
        return [];
    }
}
