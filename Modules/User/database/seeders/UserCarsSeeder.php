<?php

namespace Modules\User\Database\Seeders;

use App\Helpers\Utils;
use Illuminate\Database\Seeder;
use Modules\Car\Models\Car;
use Modules\Car\Models\UserCar;
use Modules\User\Models\User;

class UserCarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $cars = Car::all();
        $users = User::all();
        $cars->each(function ($car) use ($users, $faker) {
            $users->each(function ($user) use ($car, $faker) {
                if ($faker->boolean(99)) {
                    return;
                }
                $slug = Utils::generateUniqueSlug($user->first_name.' '.$user->last_name.'-car', UserCar::class);

                UserCar::factory()
                    ->for($user)
                    ->for($car)
                    ->count(1)
                    ->create([
                        'slug' => $slug,
                    ]);
            });
        });

    }
}
