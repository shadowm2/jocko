<?php

namespace Modules\Inventory\Livewire\WarehouseItems;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Modules\Inventory\Services\WarehouseItemService;

class WarehouseItemPicker extends Component
{
    #[Reactive]
    public ?string $warehouse = null;

    /**
     * Array of selected warehouse item slugs.
     *
     * @var array<int, string>
     */
    #[Modelable]
    public array $value = [];

    public ?string $placeholder = null;

    /**
     * Lightweight data specifically for Alpine.
     */
    public array $items = [];

    /**
     * Cache the currently loaded warehouse so render() doesn't
     * repeatedly rebuild the same collection.
     */
    protected ?string $loadedWarehouse = null;

    public function updatedWarehouse(): void
    {
        if ($this->loadedWarehouse === $this->warehouse) {
            return;
        }

        $this->value = [];
        $this->loadItems();
    }

    public function mount(): void
    {
        $this->loadItems();
    }

    protected function loadItems(): void
    {
        if ($this->loadedWarehouse === $this->warehouse) {
            return;
        }

        $this->loadedWarehouse = $this->warehouse;

        if (! $this->warehouse) {
            $this->items = [];

            return;
        }

        $items = resolve(WarehouseItemService::class)
            ->getItemsInWarehouse(
                $this->warehouse,
                paginate: false,
            );

        $this->items = $items
            ->map(static fn ($warehouseItem) => [
                'slug' => $warehouseItem->slug,
                'item' => [
                    'name' => $warehouseItem->item->name,
                ],
                'quantity' => $warehouseItem->quantity,
            ])
            ->values()
            ->all();
    }

    public function render(): View
    {
        return view('inventory::components.warehouse-item-picker');
    }
}
