<?php

namespace Modules\Inventory\Livewire\Warehouses;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\WarehouseService;

class WarehousePicker extends Component
{
    /** @var Collection<int, Warehouse>|array<never, never> */
    public $warehouses = [];

    public ?string $selectedWarehouse = null;

    #[On('active-warehouse-changed')]
    public function warehouseChanged(?string $warehouse): void
    {
        $this->selectedWarehouse = $warehouse;
    }

    /** @param string|null $slug */
    public function updatedSelectedWarehouse($slug): void
    {
        if (empty($slug)) {
            return;
        }

        $warehouseService = resolve(WarehouseService::class);
        $warehouseService->setActiveWarehouse($slug);
        $this->dispatch(
            'active-warehouse-changed',
            warehouse: $slug
        );
    }

    public function mount(WarehouseService $service, ?Warehouse $currentWarehouse = null): void
    {
        if ($currentWarehouse) {
            $this->selectedWarehouse = $currentWarehouse->slug;
        } else {
            $this->selectedWarehouse = resolve(WarehouseService::class)->getActiveWarehouse()?->slug;
        }
        $this->warehouses = $service->getWarehouses(paginate: false);
    }

    public function render(): View
    {
        return view('inventory::livewire.warehouse-picker');
    }
}
