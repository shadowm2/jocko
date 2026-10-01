<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\ItemBrand;

class ItemBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = ItemBrand::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $basePrice = $this->faker->numberBetween($min = 10, $max = 500);
        $purchasePrice = $basePrice * 50000;
        $salePrice = $purchasePrice + (int) ($purchasePrice * 0.05);

        return [
            'sku' => $this->faker->uuid(),
            'barcode' => $this->faker->md5(),
            'part_number' => $this->faker->randomNumber(9),
            'purchase_price' => $purchasePrice,
            'sale_price' => $salePrice,
            'is_active' => $this->faker->boolean(95),
        ];
    }
}
