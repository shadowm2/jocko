<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Dashboard\Models\City;
use Modules\Inventory\Models\Supplier;
use Modules\User\Models\User;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::all();
        if (User::count() < 50) {
            $users = User::factory()->count(50)->create();
        } else {
            $users = User::all();
        }
        foreach ($users->random(50) as $user) {
            $slug = 'supplier-'.$user->slug;
            if (Supplier::where('slug', $slug)->exists()) {
                continue;
            }
            $city = $cities->random();
            Supplier::factory()
                ->for($user)
                ->for($city)
                ->create([
                    'slug' => $slug,
                ]);
        }
    }
}
