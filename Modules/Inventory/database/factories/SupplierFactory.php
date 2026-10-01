<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Supplier;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Iran home phone numbers format: 0XXX-XXX-XXXX
        // Area codes: 021 (Tehran), 031 (Isfahan), 051 (Mashhad), etc.

        $areaCodes = [
            '021', // Tehran
            '031', // Isfahan
            '041', // Tabriz
            '051', // Mashhad
            '071', // Shiraz
            '061', // Ahvaz
            '081', // Hamadan
            '011', // Sari
            '013', // Rasht
            '017', // Gorgan
            '023', // Semnan
            '025', // Qom
            '026', // Karaj
            '028', // Qazvin
            '034', // Kerman
            '035', // Yazd
            '038', // Shahrekord
            '044', // Urmia
            '045', // Ardabil
            '066', // Khorramabad
            '083', // Kermanshah
            '084', // Ilam
            '086', // Arak
            '087', // Sanandaj
            '088', // Zahedan
        ];

        $areaCode = $areaCodes[array_rand($areaCodes)];
        $number = rand(1000000, 9999999); // 7 digits
        $formatted = sprintf('%s-%s-%s',
            $areaCode,
            substr($number, 0, 3),
            substr($number, 3, 4)
        );

        return [
            'phone' => $formatted,
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'website' => $this->faker->url(),
            'tax_number' => $this->faker->buildingNumber(),
            'is_active' => $this->faker->boolean(80),
            'notes' => $this->faker->boolean() ? $this->faker->text() : null,
        ];
    }
}
