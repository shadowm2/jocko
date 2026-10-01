<?php

namespace Modules\Inventory\Database\Seeders;

use App\Helpers\Utils;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseItem;

class WarehouseItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all();
        $warehouses = Warehouse::with(['warehouseItems'])->get();
        $items->each(function (Item $item) use ($warehouses) {
            $warehouses->each(function (Warehouse $warehouse) use ($item) {
                if (WarehouseItem::where('item_id', $item->id)->where('warehouse_id', $warehouse->id)->exists()) {
                    return;
                }
                if (Factory::create()->boolean(3)) {
                    return;
                }
                /** @var WarehouseItem $wi */
                $wi = WarehouseItem::factory()
                    ->for($item)
                    ->for($warehouse)
                    ->make();
                $wi->slug = Utils::generateUniqueSlug('wiseed '.$warehouse->slug.' '.$item->slug, WarehouseItem::class);
                $wi->save();
            });
        });
    }
}
