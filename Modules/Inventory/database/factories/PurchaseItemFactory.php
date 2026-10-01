<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\PurchaseItem;

/**
 * @extends Factory<PurchaseItem>
 */
class PurchaseItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = PurchaseItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $quantity = fake()->randomFloat(2, 1, 100);
        $unitPrice = fake()->randomFloat(2, 10, 1000);

        return [
            // Purchases and items are seeded separately; pick existing ones.
            'purchase_id' => Purchase::query()->inRandomOrder()->value('id'),
            'item_id' => Item::query()->inRandomOrder()->value('id'),
            'quantity' => $quantity,
            'received_quantity' => 0,
            'unit_price' => $unitPrice,
            'discount' => 0,
            'tax' => 0,
            'total' => round($quantity * $unitPrice, 2),
        ];
    }
}
