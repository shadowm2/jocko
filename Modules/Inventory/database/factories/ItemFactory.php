<?php

namespace Modules\Inventory\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Dashboard\Models\Category;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\UnitGroup;

/** @extends Factory<Item> */
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
        /** @var string $name */
        $name = $this->faker->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Utils::generateUniqueSlug($name, Item::class),
            // Unit groups and categories are seeded separately; pick existing ones.
            'unit_group_id' => UnitGroup::query()->inRandomOrder()->value('id'),
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(95),
        ];
    }
}
