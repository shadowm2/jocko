<?php

namespace Modules\Inventory\Database\Seeders;

use App\Helpers\Utils;
use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::factory()->count(20)->make()->each(function (Warehouse $warehouse) {
            $warehouse->slug = Utils::generateUniqueSlug($warehouse->name, Warehouse::class);
            $warehouse->save();
        });
    }
}
