<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Inventory\Models\PurchaseItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

