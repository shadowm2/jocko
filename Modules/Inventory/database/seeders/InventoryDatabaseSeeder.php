<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;

class InventoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UnitGroupSeeder::class,
            UnitSeeder::class,
            BrandSeeder::class,
            SupplierSeeder::class,
            InventoryItemSeeder::class,
            WarehouseSeeder::class,
            WarehouseItemSeeder::class,
        ]);
    }
}
