<?php

namespace Modules\Car\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Car\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            Color::factory()->count(10)->create();
        } catch (\Exception $e) {
            $this->command->info($e->getMessage());
        }
    }
}
