<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\UnitGroup;

class UnitGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitGroups = [
            [
                'slug' => 'length',
                'name' => 'طول',
            ],
            [
                'slug' => 'weight',
                'name' => 'وزن',
            ],
            [
                'slug' => 'volume',
                'name' => 'حجم',
            ],
            [
                'slug' => 'area',
                'name' => 'مساحت',
            ],
            [
                'slug' => 'time',
                'name' => 'زمان',
            ],
            [
                'slug' => 'temperature',
                'name' => 'دما',
            ],
            [
                'slug' => 'quantity',
                'name' => 'تعداد',
            ],
            [
                'slug' => 'speed',
                'name' => 'سرعت',
            ],
        ];

        foreach ($unitGroups as $unitGroup) {
            UnitGroup::updateOrCreate([
                'slug' => $unitGroup['slug'],
            ], [
                'name' => $unitGroup['name'],
            ]);
        }
    }
}
