<?php

namespace Modules\Inventory\Livewire\Warehouses;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\WarehouseService;

class WarehouseList extends Component
{
    use InteractsWithTableFilters;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
        'is_active' => [],
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
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('inventory::strings.Search Warehouses')],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('inventory::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }

    public function toggleIsActive($warehouseSlug): void
    {
        $warehouseService = resolve(WarehouseService::class);
        /** @var Warehouse $country */
        $warehouse = $warehouseService->findByKey($warehouseSlug);
        $warehouseService->update($warehouse, [
            'is_active' => ! $warehouse->is_active,
        ]);
        Flux::toast(__('inventory::messages.Warehouse Updated Successfully'), variant: 'success');
    }

    // This triggers a re-render
    #[On('active-warehouse-changed')]
    public function activeWarehouseChanged($warehouse) {}

    public function changeActiveWarehouse($slug): void
    {
        $warehouseService = resolve(WarehouseService::class);
        $warehouse = $warehouseService->findByKey($slug);
        $warehouseService->setActiveWarehouse($warehouse);
    }

    public function render(): View
    {
        $warehouseService = resolve(WarehouseService::class);
        $activeWarehouse = $warehouseService->getActiveWarehouse();
        $columns = [
            [
                'label' => __('inventory::attributes.Warehouse Name'),
                'component' => 'inventory::warehouse-name-cell',
                'attrs' => fn ($row) => [
                    'activeWarehouse' => $activeWarehouse,
                ],
            ],
            [
                'label' => __('inventory::attributes.Warehouse Description'),
                'key' => 'description',
                'maxlength' => 15,
            ],
            [
                'label' => __('inventory::attributes.Warehouse Description'),
                'key' => 'warehouse_items_count',
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => '',
                'component' => 'inventory::warehouse-action-cell',
            ],
        ];
        $rows = $warehouseService->getWarehouses($this->filters);

        return view('inventory::livewire.warehouses-list', [
            ...compact('columns', 'rows', 'activeWarehouse'),
            'filterDefinitions' => $this->filterDefinitions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Warehouses'),
            ]);
    }
}
