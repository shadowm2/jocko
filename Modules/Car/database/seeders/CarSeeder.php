<?php

namespace Modules\Car\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Car\Models\Car;
use Modules\Car\Models\CarCompany;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carCompanies = CarCompany::all();

        $carCompanies->each(function (CarCompany $carCompany) {
            Car::factory()
                ->for($carCompany, 'company')
                ->count(10)->create();
        });

    }
}
