<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Order\Enums\PaymentMethod;
use Modules\Order\Models\Payment;

/** @extends Factory<Payment> */
class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // The order is supplied by the caller (->for($order)).
        return [
            'slug' => 'pay-'.Str::lower(Str::random(12)),
            'amount' => $this->faker->numberBetween(10, 500) * 1000,
            'method' => $this->faker->randomElement(PaymentMethod::cases()),
            'paid_at' => $this->faker->dateTimeBetween('-60 days'),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
