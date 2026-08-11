<?php

namespace Modules\Car\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Car\Models\Car;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->streetName();
        $slug = Utils::generateUniqueSlug($name, Car::class);

        return [
            ...compact('name', 'slug'),
        ];
    }
}
