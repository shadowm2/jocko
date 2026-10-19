<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\StockMovement;

/** @extends Factory<StockMovement> */
class StockMovementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = StockMovement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
