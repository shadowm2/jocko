<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Car\Models\Color;
use Modules\Car\Models\UserCar;
use Morilog\Jalali\Jalalian;

class UserCarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = UserCar::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $manufacturedAt = Jalalian::fromDateTime($this->faker->dateTimeBetween('-20 years', 'now'))
            ->format('Y/m/d');

        return [
            'manufactured_at' => $manufacturedAt,
            'color_id' => $this->faker->randomElement(Color::all())->id,
            'description' => $this->faker->boolean(30) ? $this->faker->text(500) : null,
        ];
    }
}
