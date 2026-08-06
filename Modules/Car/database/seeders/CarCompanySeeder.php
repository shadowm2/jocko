<?php

namespace Modules\Car\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Car\Models\CarCompany;

class CarCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarCompany::factory()->count(10)->create();

    }
}
