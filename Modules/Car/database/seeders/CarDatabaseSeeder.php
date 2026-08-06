<?php

namespace Modules\Car\Database\Seeders;

use Illuminate\Database\Seeder;

class CarDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CarCompanySeeder::class,
            CarSeeder::class,
            ColorSeeder::class,
        ]);
    }
}
