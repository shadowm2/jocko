<?php

namespace Modules\Car\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Car\Models\Car;
use Modules\Car\Models\CarCompany;

class CarCompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = CarCompany::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->name();
        $slug = Utils::generateUniqueSlug($name, Car::class);

        return [
            'name' => $name,
            'slug' => $slug,
        ];
    }
}
