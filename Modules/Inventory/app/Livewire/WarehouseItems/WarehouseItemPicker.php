<?php

namespace Modules\Inventory\Livewire\WarehouseItems;

use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Modules\Inventory\Services\WarehouseItemService;
use Modules\Inventory\Services\WarehouseService;

class WarehouseItemPicker extends Component
{
    #[Reactive]
    public ?string $warehouse = null;

    public bool $showWarehousePicker = false;

    public ?string $selectedWarehouse = null;

    public ?string $warehouseName = null;

    public array $warehouses = [];

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
        if ($this->showWarehousePicker) {
            $this->selectedWarehouse = $this->warehouse;
        } else {
            $this->setWarehouseName($this->warehouse);
        }

        if ($this->loadedWarehouse === $this->warehouse) {
            return;
        }

        $this->value = [];
        $this->loadItems();
    }

    public function updatedSelectedWarehouse(): void
    {
        if ($this->loadedWarehouse === $this->selectedWarehouse) {
            return;
        }

        $this->value = [];
        $this->loadItems();
        $this->dispatch('warehouse-selected', warehouse: $this->selectedWarehouse);
    }

    public function mount(): void
    {
        $this->selectedWarehouse = $this->warehouse ?? '';

        if ($this->showWarehousePicker) {
            $this->warehouses = resolve(WarehouseService::class)
                ->getWarehouses(paginate: false)
                ->map(static fn ($warehouse) => [
                    'slug' => $warehouse->slug,
                    'name' => $warehouse->name,
                ])
                ->all();
        } else {
            if (blank($this->warehouse)) {
                throw new InvalidArgumentException('The warehouse prop is required when the warehouse picker is hidden.');
            }

            $this->setWarehouseName($this->warehouse);
        }

        $this->loadItems();
    }

    protected function setWarehouseName(?string $warehouseSlug): void
    {
        if (blank($warehouseSlug)) {
            throw new InvalidArgumentException('The warehouse prop is required when the warehouse picker is hidden.');
        }

        $warehouse = resolve(WarehouseService::class)->findByKey($warehouseSlug);

        if (! $warehouse) {
            throw new InvalidArgumentException("Warehouse [{$warehouseSlug}] was not found.");
        }

        $this->warehouseName = $warehouse->name;
    }

    protected function loadItems(): void
    {
        $warehouse = $this->showWarehousePicker ? $this->selectedWarehouse : $this->warehouse;

        if ($this->loadedWarehouse === $warehouse) {
            return;
        }

        $this->loadedWarehouse = $warehouse;

        if (! $warehouse) {
            $this->items = [];

            return;
        }

        $items = resolve(WarehouseItemService::class)
            ->getItemsInWarehouse(
                $warehouse,
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
