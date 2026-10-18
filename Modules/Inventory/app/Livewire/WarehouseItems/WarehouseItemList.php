<?php

namespace Modules\Inventory\Livewire\WarehouseItems;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Services\WarehouseItemService;
use Modules\Inventory\Services\WarehouseService;

class WarehouseItemList extends Component
{
    use WithPagination;

    #[On('active-warehouse-changed')]
    public function onWarehouseSelect(string $warehouse): void {}

    public function render(): View
    {
        $warehouseService = resolve(WarehouseService::class);
        $warehouseItemService = resolve(WarehouseItemService::class);
        $warehouse = $warehouseService->getActiveWarehouse();
        $rows = $warehouseItemService->getItemsInWarehouse($warehouse);

        $columns = [
            [
                'label' => __('inventory::attributes.Item Name'),
            ],
            [
                'label' => __('inventory::attributes.Warehouse Item Quantity'),
            ],
            [
                'label' => __('inventory::attributes.Warehouse Item Min Quantity'),
            ],
            [
                'label' => __('inventory::attributes.Warehouse Item Max Quantity'),
            ],
            [
                'label' => __('inventory::attributes.Stock Status'),
                'component' => 'inventory::warehouse-item-stock-status-cell',
            ],
            [
                'label' => '',
                'component' => 'inventory::warehouse-item-action-cell',
            ],
        ];

        return view('inventory::livewire.warehouse-items-list', [
            ...compact('columns', 'rows', 'warehouse'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Warehouses'),
            ]);
    }
}
