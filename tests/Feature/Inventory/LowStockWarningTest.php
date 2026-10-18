<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Modules\Inventory\Enums\StockMovementType;
use Modules\Inventory\Events\StockRunningLow;
use Modules\Inventory\Livewire\StockMovements\StockAdjustmentCreate;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Models\UnitGroup;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseItem;
use Modules\Inventory\Services\StockMovementService;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;

// Bare rows only (see OrderStockTest): foreign key checks are deferred.
beforeEach(function () {
    DB::statement('PRAGMA defer_foreign_keys = ON');

    $group = UnitGroup::forceCreate(['name' => 'Count', 'slug' => 'count']);
    $this->piece = Unit::forceCreate(['name' => 'Piece', 'symbol' => 'pc', 'unit_group_id' => $group->id, 'conversion_factor' => 1, 'is_base' => true, 'slug' => 'piece']);
    $item = Item::forceCreate(['name' => 'Oil', 'slug' => 'oil', 'unit_group_id' => $group->id, 'category_id' => 1]);
    $this->main = Warehouse::forceCreate(['name' => 'Main', 'slug' => 'main']);
    $this->spare = Warehouse::forceCreate(['name' => 'Spare', 'slug' => 'spare']);
    $this->stock = WarehouseItem::forceCreate(['slug' => 'main-oil', 'warehouse_id' => $this->main->id, 'item_id' => $item->id, 'quantity' => 30, 'min_quantity' => 10]);

    $this->adjust = fn (float $delta) => resolve(StockMovementService::class)->adjust($this->stock, $delta, StockMovementType::ADJUSTMENT);
    $this->warned = fn () => Event::dispatched(StockRunningLow::class)->count();
});

it('warns when an adjustment leaves the item at or below its minimum', function () {
    Event::fake([StockRunningLow::class]);

    ($this->adjust)(-10);
    expect(($this->warned)())->toBe(0);

    ($this->adjust)(-10);
    Event::assertDispatched(StockRunningLow::class, fn (StockRunningLow $event) => $event->warehouseItem->is($this->stock) && (float) $event->warehouseItem->quantity === 10.0);
});

it('does not warn for stock coming in, or for an item without a minimum', function () {
    Event::fake([StockRunningLow::class]);

    $this->stock->update(['quantity' => 5]);
    ($this->adjust)(1);
    expect(($this->warned)())->toBe(0);

    $this->stock->update(['quantity' => 5, 'min_quantity' => null]);
    ($this->adjust)(-1);
    expect(($this->warned)())->toBe(0);
});

it('warns when a transfer leaves the source low, and when an order does', function () {
    Event::fake([StockRunningLow::class]);

    resolve(StockMovementService::class)->transfer($this->stock, $this->spare, 25);
    expect(($this->warned)())->toBe(1);

    $order = Order::forceCreate([
        'slug' => 'o1', 'user_id' => 1, 'user_car_id' => 1, 'warehouse_id' => $this->main->id,
        'order_number' => 'ORD-00001', 'status' => OrderStatus::PENDING,
    ]);
    WarehouseItem::whereKey($this->stock->id)->update(['quantity' => 30]);
    resolve(OrderService::class)->update($order, ['items' => ['main-oil' => ['quantity' => 25, 'unit_price' => 5, 'unit_id' => $this->piece->id]]]);
    expect(($this->warned)())->toBe(2);
});

it('does not warn about a change that was rolled back', function () {
    Event::fake([StockRunningLow::class]);

    try {
        DB::transaction(function () {
            ($this->adjust)(-25);
            throw new RuntimeException('later step failed');
        });
    } catch (RuntimeException) {
    }

    expect(($this->warned)())->toBe(0)
        ->and((float) $this->stock->fresh()->quantity)->toBe(30.0);
});

it('shows a warning toast on the adjustment page, and only when the stock runs low', function () {
    $warning = fn ($event, $params) => ($params['dataset']['variant'] ?? null) === 'warning';
    $adjust = fn (string $quantity) => Livewire::test(StockAdjustmentCreate::class)
        ->set('form.warehouse_item', 'main-oil')
        ->set('form.type', 'damage')
        ->set('form.quantity', $quantity)
        ->call('save');

    $adjust('5')->assertHasNoErrors()->assertNotDispatched('toast-show', $warning);
    $adjust('15')->assertHasNoErrors()->assertDispatched('toast-show', $warning);
});
