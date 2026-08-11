<?php

namespace Modules\Order\Database\Seeders;

use App\Helpers\Utils;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\Car\Models\UserCar;
use Modules\Order\app\Models\Order;
use Modules\User\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::admin()
            ->get();

        $userCars = UserCar::all();
        $faker = Factory::create();

        foreach ($users as $user) {
            $currentUserCars = $userCars->filter(fn ($userCar) => $userCar->user_id == $user->id);
            foreach ($currentUserCars as $userCar) {
                if ($faker->boolean(90)) {
                    continue;
                }
                $slug = Utils::generateUniqueSlug($user->initials().' '.$userCar->name, Order::class);
                Order::factory()
                    ->for($user)
                    ->for($userCar)
                    ->count(1)
                    ->create([
                        'slug' => $slug,
                    ]);
            }
        }
    }
}
