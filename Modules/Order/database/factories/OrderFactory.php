<?php

namespace Modules\Order\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Warehouse;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $orderNumber = Order::nextNumber();

        return [
            'slug' => Utils::generateUniqueSlug($orderNumber, Order::class),
            // Warehouses are seeded separately; pick an existing one.
            'warehouse_id' => Warehouse::query()->inRandomOrder()->value('id'),
            'order_number' => $orderNumber,
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
