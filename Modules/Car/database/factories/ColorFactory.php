<?php

namespace Modules\Car\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Car\Models\Color;

/**
 * @extends Factory<Color>
 */
class ColorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Color::class;

    /**
     * Define the model's default state.
     *
     * @throws \Exception
     */
    public function definition(): array
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

        $startTime = microtime(true);
        do {
            $randomColor = $this->faker->randomElement($colors);
            $duration = microtime(true) - $startTime;
            if ($duration > 10) {
                throw new \Exception('All colors are seeded');
            }
        } while (Color::where('title', '=', $randomColor['title'])->exists() === true);

        $name = $randomColor['name'];
        $slug = Utils::generateUniqueSlug($randomColor['title'], Color::class);

        return [
            'name' => $name,
            'slug' => $slug,
            'hex' => $randomColor['hex'],
            'title' => $randomColor['title'],
        ];
    }
}
