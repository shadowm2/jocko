<?php

namespace Modules\Car\Database\Seeders;

use App\Helpers\Utils;
use Illuminate\Database\Seeder;
use Modules\Car\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            [
                'title' => 'Black',
                'name' => 'سیاه',
                'hex' => '#000000',

            ],
            [
                'title' => 'White',
                'name' => 'سفید',
                'hex' => '#FFFFFF',
            ],
            [
                'title' => 'Silver',
                'name' => 'نقره‌ای',
                'hex' => '#C0C0C0',
            ],
            [
                'title' => 'Gray',
                'name' => 'خاکستری',
                'hex' => '#808080',
            ],
            [
                'title' => 'Red',
                'name' => 'قرمز',
                'hex' => '#FF0000',
            ],
            [
                'title' => 'Blue',
                'name' => 'آبی',
                'hex' => '#0000FF',
            ],
            [
                'title' => 'Green',
                'name' => 'سبز',
                'hex' => '#008000',
            ],
            [
                'title' => 'Yellow',
                'name' => 'زرد',
                'hex' => '#FFFF00',
            ],
            [
                'title' => 'Orange',
                'name' => 'نارنجی',
                'hex' => '#FFA500',
            ],
            [
                'title' => 'Brown',
                'name' => 'قهوه‌ای',
                'hex' => '#A52A2A',
            ],
            [
                'title' => 'Gold',
                'name' => 'طلایی',
                'hex' => '#FFD700',
            ],
            [
                'title' => 'Navy Blue',
                'hex' => '#000080',
                'name' => 'آبی اقیانوسی',
            ],
            [
                'title' => 'Metallic Gray',
                'hex' => '#6E6E6E',
                'name' => 'خاکستری فلزی',
            ],
            [
                'title' => 'Pearl White',
                'hex' => '#F8F6F0',
                'name' => 'سفید مرواریدی',
            ],
            [
                'title' => 'Champagne',
                'name' => 'شامپاین',
                'hex' => '#F7E7CE',
            ],
        ];

        foreach ($colors as $color) {
            $slug = Utils::generateBaseSlug($color['title']);
            Color::updateOrCreate([
                'slug' => $slug,
            ], $color);
        }
    }
}
