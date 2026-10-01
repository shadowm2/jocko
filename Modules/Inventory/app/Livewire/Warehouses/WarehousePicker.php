<?php

namespace Modules\Inventory\Livewire\Warehouses;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\WarehouseService;

class WarehousePicker extends Component
{
    public $warehouses = [];

    public ?string $selectedWarehouse = null;

    #[On('active-warehouse-changed')]
    public function warehouseChanged($warehouse): void
    {
        $this->selectedWarehouse = $warehouse;
    }

    public function updatedSelectedWarehouse($slug): void
    {
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
