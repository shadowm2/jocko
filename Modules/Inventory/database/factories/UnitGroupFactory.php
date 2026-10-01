<?php

namespace Modules\Inventory\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\UnitGroup;

/**
 * @extends Factory<UnitGroup>
 */
class UnitGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = UnitGroup::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->word();
        $slug = Utils::generateUniqueSlug($name, $this->model);

        return [
            'name' => $name,
            'slug' => $slug,
        ];
    }
}
