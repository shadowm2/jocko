<?php

namespace Modules\Inventory\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Enums\PurchaseStatus;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Models\Warehouse;

class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $orderNumber = $this->faker->unique()->numerify('PO-#####');

        return [
            'slug' => Utils::generateUniqueSlug($orderNumber, Purchase::class),
            // Suppliers and warehouses are seeded separately; pick existing ones.
            'supplier_id' => Supplier::query()->inRandomOrder()->value('id'),
            'warehouse_id' => Warehouse::query()->inRandomOrder()->value('id'),
            'order_number' => $orderNumber,
            'status' => PurchaseStatus::getDefault(),
            'ordered_at' => now(),
            'expected_at' => now()->addWeek(),
            'received_at' => null,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
