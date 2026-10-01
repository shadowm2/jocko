<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\UnitGroup;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // طول
            [
                'slug' => 'millimeter',
                'name' => 'میلی‌متر',
                'symbol' => 'mm',
                'conversion_factor' => 0.001,
                'is_base' => false,
                'unit_group' => 'length',
            ],
            [
                'slug' => 'centimeter',
                'name' => 'سانتی‌متر',
                'symbol' => 'cm',
                'conversion_factor' => 0.01,
                'is_base' => false,
                'unit_group' => 'length',
            ],
            [
                'slug' => 'meter',
                'name' => 'متر',
                'symbol' => 'm',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'length',
            ],
            [
                'slug' => 'kilometer',
                'name' => 'کیلومتر',
                'symbol' => 'km',
                'conversion_factor' => 1000,
                'is_base' => false,
                'unit_group' => 'length',
            ],
            [
                'slug' => 'inch',
                'name' => 'اینچ',
                'symbol' => 'in',
                'conversion_factor' => 0.0254,
                'is_base' => false,
                'unit_group' => 'length',
            ],
            [
                'slug' => 'foot',
                'name' => 'فوت',
                'symbol' => 'ft',
                'conversion_factor' => 0.3048,
                'is_base' => false,
                'unit_group' => 'length',
            ],

            // وزن
            [
                'slug' => 'milligram',
                'name' => 'میلی‌گرم',
                'symbol' => 'mg',
                'conversion_factor' => 0.000001,
                'is_base' => false,
                'unit_group' => 'weight',
            ],
            [
                'slug' => 'gram',
                'name' => 'گرم',
                'symbol' => 'g',
                'conversion_factor' => 0.001,
                'is_base' => false,
                'unit_group' => 'weight',
            ],
            [
                'slug' => 'kilogram',
                'name' => 'کیلوگرم',
                'symbol' => 'kg',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'weight',
            ],
            [
                'slug' => 'ton',
                'name' => 'تن',
                'symbol' => 't',
                'conversion_factor' => 1000,
                'is_base' => false,
                'unit_group' => 'weight',
            ],
            [
                'slug' => 'ounce',
                'name' => 'اونس',
                'symbol' => 'oz',
                'conversion_factor' => 0.0283495,
                'is_base' => false,
                'unit_group' => 'weight',
            ],
            [
                'slug' => 'pound',
                'name' => 'پوند',
                'symbol' => 'lb',
                'conversion_factor' => 0.453592,
                'is_base' => false,
                'unit_group' => 'weight',
            ],

            // حجم
            [
                'slug' => 'milliliter',
                'name' => 'میلی‌لیتر',
                'symbol' => 'ml',
                'conversion_factor' => 0.001,
                'is_base' => false,
                'unit_group' => 'volume',
            ],
            [
                'slug' => 'liter',
                'name' => 'لیتر',
                'symbol' => 'L',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'volume',
            ],
            [
                'slug' => 'cubic-meter',
                'name' => 'متر مکعب',
                'symbol' => 'm³',
                'conversion_factor' => 1000,
                'is_base' => false,
                'unit_group' => 'volume',
            ],
            [
                'slug' => 'gallon',
                'name' => 'گالن',
                'symbol' => 'gal',
                'conversion_factor' => 3.78541,
                'is_base' => false,
                'unit_group' => 'volume',
            ],

            // مساحت
            [
                'slug' => 'square-centimeter',
                'name' => 'سانتی‌متر مربع',
                'symbol' => 'cm²',
                'conversion_factor' => 0.0001,
                'is_base' => false,
                'unit_group' => 'area',
            ],
            [
                'slug' => 'square-meter',
                'name' => 'متر مربع',
                'symbol' => 'm²',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'area',
            ],
            [
                'slug' => 'square-kilometer',
                'name' => 'کیلومتر مربع',
                'symbol' => 'km²',
                'conversion_factor' => 1000000,
                'is_base' => false,
                'unit_group' => 'area',
            ],

            // زمان
            [
                'slug' => 'second',
                'name' => 'ثانیه',
                'symbol' => 's',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'time',
            ],
            [
                'slug' => 'minute',
                'name' => 'دقیقه',
                'symbol' => 'min',
                'conversion_factor' => 60,
                'is_base' => false,
                'unit_group' => 'time',
            ],
            [
                'slug' => 'hour',
                'name' => 'ساعت',
                'symbol' => 'h',
                'conversion_factor' => 3600,
                'is_base' => false,
                'unit_group' => 'time',
            ],
            [
                'slug' => 'day',
                'name' => 'روز',
                'symbol' => 'd',
                'conversion_factor' => 86400,
                'is_base' => false,
                'unit_group' => 'time',
            ],

            // دما
            [
                'slug' => 'celsius',
                'name' => 'درجه سانتی‌گراد',
                'symbol' => '°C',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'temperature',
            ],
            [
                'slug' => 'fahrenheit',
                'name' => 'درجه فارنهایت',
                'symbol' => '°F',
                'conversion_factor' => 1,
                'is_base' => false,
                'unit_group' => 'temperature',
            ],

            // تعداد
            [
                'slug' => 'piece',
                'name' => 'عدد',
                'symbol' => 'عدد',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'quantity',
            ],
            [
                'slug' => 'dozen',
                'name' => 'دوجین',
                'symbol' => 'دوجین',
                'conversion_factor' => 12,
                'is_base' => false,
                'unit_group' => 'quantity',
            ],
            [
                'slug' => 'pair',
                'name' => 'جفت',
                'symbol' => 'جفت',
                'conversion_factor' => 2,
                'is_base' => false,
                'unit_group' => 'quantity',
            ],
            [
                'slug' => 'set',
                'name' => 'دست',
                'symbol' => 'دست',
                'conversion_factor' => 1,
                'is_base' => false,
                'unit_group' => 'quantity',
            ],

            // سرعت
            [
                'slug' => 'meter-per-second',
                'name' => 'متر بر ثانیه',
                'symbol' => 'm/s',
                'conversion_factor' => 1,
                'is_base' => true,
                'unit_group' => 'speed',
            ],
            [
                'slug' => 'kilometer-per-hour',
                'name' => 'کیلومتر بر ساعت',
                'symbol' => 'km/h',
                'conversion_factor' => 0.277778,
                'is_base' => false,
                'unit_group' => 'speed',
            ],
            [
                'slug' => 'mile-per-hour',
                'name' => 'مایل بر ساعت',
                'symbol' => 'mph',
                'conversion_factor' => 0.44704,
                'is_base' => false,
                'unit_group' => 'speed',
            ],
        ];

        $unitGroups = UnitGroup::all();
        foreach ($units as $unit) {
            $unitGroup = $unitGroups->where('slug', $unit['unit_group'])->first();
            if (! $unitGroup) {
                dd('Unit group not found: '.$unit['unit_group']);
            }
            $unitGroup->units()->updateOrCreate([
                'slug' => $unit['slug'],
            ], [
                'name' => $unit['name'],
                'symbol' => $unit['symbol'],
                'conversion_factor' => $unit['conversion_factor'],
                'is_base' => $unit['is_base'],
            ]);
        }
    }
}
