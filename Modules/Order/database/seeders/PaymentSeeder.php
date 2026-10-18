<?php

namespace Modules\Order\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        Order::query()
            ->where('status', '!=', OrderStatus::CANCELLED->value)
            ->withSum('items', 'total')
            ->get()
            ->each(function (Order $order) use ($faker) {
                // Leave some orders unpaid, some partly paid, most settled in one or two payments.
                $remaining = round((float) $order->items_sum_total * $faker->randomElement([0, 0.5, 1, 1]), -3);
                $parts = $faker->numberBetween(1, 2);

                for ($part = 1; $part <= $parts && $remaining > 0; $part++) {
                    $amount = $part === $parts ? $remaining : round($remaining / 2, -3);

                    Payment::factory()->for($order)->create(['amount' => $amount]);
                    $remaining -= $amount;
                }
            });
    }
}
