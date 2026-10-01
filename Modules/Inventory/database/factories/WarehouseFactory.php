<?php

namespace Modules\Inventory\Database\Factories;

use App\Helpers\Utils;
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
        $name = $this->faker->unique()->company();

        return [
            'slug' => Utils::generateUniqueSlug($name, Warehouse::class),
            'name' => $name,
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}