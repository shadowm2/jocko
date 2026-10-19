<?php

namespace Modules\Inventory\Livewire\WarehouseItems;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Services\WarehouseItemService;
use Modules\Inventory\Services\WarehouseService;

class WarehouseItemList extends Component
{
    use InteractsWithTableFilters;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
    ];

    /** @var array<string, mixed> */
    public array $filters = self::FILTERS;

    protected function defaultTableFilters(): array
    {
        return self::FILTERS;
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('inventory::strings.Search Warehouse Items')],
        ];
    }

    #[On('active-warehouse-changed')]
    public function onWarehouseSelect(string $warehouse): void {}

    public function render(): View
    {
        $warehouseService = resolve(WarehouseService::class);
        $warehouseItemService = resolve(WarehouseItemService::class);
        $warehouse = $warehouseService->getActiveWarehouse();
        $rows = $warehouseItemService->getItemsInWarehouse($warehouse, $this->filters);

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
            'filterDefinitions' => $this->filterDefinitions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Warehouses'),
            ]);
    }
}
