<?php

namespace Modules\Dashboard\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Models\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            [
                'name' => 'آذربایجان شرقی',
                'slug' => 'east-azerbaijan',
            ],
            [
                'name' => 'آذربایجان غربی',
                'slug' => 'west-azerbaijan',
            ],
            [
                'name' => 'اردبیل',
                'slug' => 'ardabil',
            ],
            [
                'name' => 'اصفهان',
                'slug' => 'isfahan',
            ],
            [
                'name' => 'البرز',
                'slug' => 'alborz',
            ],
            [
                'name' => 'ایلام',
                'slug' => 'ilam',
            ],
            [
                'name' => 'بوشهر',
                'slug' => 'bushehr',
            ],
            [
                'name' => 'تهران',
                'slug' => 'tehran',
            ],
            [
                'name' => 'چهارمحال و بختیاری',
                'slug' => 'chaharmahal-and-bakhtiari',
            ],
            [
                'name' => 'خراسان جنوبی',
                'slug' => 'south-khorasan',
            ],
            [
                'name' => 'خراسان رضوی',
                'slug' => 'razavi-khorasan',
            ],
            [
                'name' => 'خراسان شمالی',
                'slug' => 'north-khorasan',
            ],
            [
                'name' => 'خوزستان',
                'slug' => 'khuzestan',
            ],
            [
                'name' => 'زنجان',
                'slug' => 'zanjan',
            ],
            [
                'name' => 'سمنان',
                'slug' => 'semnan',
            ],
            [
                'name' => 'سیستان و بلوچستان',
                'slug' => 'sistan-and-baluchestan',
            ],
            [
                'name' => 'فارس',
                'slug' => 'fars',
            ],
            [
                'name' => 'قزوین',
                'slug' => 'qazvin',
            ],
            [
                'name' => 'قم',
                'slug' => 'qom',
            ],
            [
                'name' => 'کردستان',
                'slug' => 'kurdistan',
                7],
            [
                'name' => 'کرمان',
                'slug' => 'kerman',
            ],
            [
                'name' => 'کرمانشاه',
                'slug' => 'kermanshah',
            ],
            [
                'name' => 'کهگیلویه و بویراحمد',
                'slug' => 'kohgiluyeh-and-boyer-ahmad',
            ],
            [
                'name' => 'گلستان',
                'slug' => 'golestan',
            ],
            [
                'name' => 'گیلان',
                'slug' => 'gilan',
            ],
            [
                'name' => 'لرستان',
                'slug' => 'lorestan',
            ],
            [
                'name' => 'مازندران',
                'slug' => 'mazandaran',
            ],
            [
                'name' => 'مرکزی',
                'slug' => 'markazi',
            ],
            [
                'name' => 'هرمزگان',
                'slug' => 'hormozgan',
            ],
            [
                'name' => 'همدان',
                'slug' => 'hamadan',
            ],
            [
                'name' => 'یزد',
                'slug' => 'yazd',
            ],
        ];
        $iran = Country::where('code', 'IR')->firstOrFail();

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                [
                    'country_id' => $iran->id,
                    'slug' => $province['slug'],
                ],
                [
                    'name' => $province['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
