<?php

namespace Modules\Inventory\Livewire\Warehouses;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;
use Modules\Inventory\Services\WarehouseService;

class WarehouseList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
        'is_active' => [],
    ];

    /** @var array<string, mixed> */
    public array $filters = self::FILTERS;

    public string $sortBy = 'warehouse_items_count';

    public string $sortDirection = 'desc';

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

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['name' => __('inventory::attributes.Warehouse Name'), 'warehouse_items_count' => __('inventory::attributes.Warehouse Items Count')];
    }

    /** @param string $warehouseSlug */
    public function toggleIsActive($warehouseSlug): void
    {
        $warehouseService = resolve(WarehouseService::class);
        $warehouse = $warehouseService->findByKeyOrFail($warehouseSlug);
        $warehouseService->update($warehouse, [
            'is_active' => ! $warehouse->is_active,
        ]);
        Flux::toast(__('inventory::messages.Warehouse Updated Successfully'), variant: 'success');
    }

    // This triggers a re-render
    /** @param string|null $warehouse */
    #[On('active-warehouse-changed')]
    public function activeWarehouseChanged($warehouse): void {}

    /** @param string $slug */
    public function changeActiveWarehouse($slug): void
    {
        $warehouseService = resolve(WarehouseService::class);
        $warehouse = $warehouseService->findByKeyOrFail($slug);
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
                'label' => __('inventory::attributes.Warehouse Items Count'),
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
        $rows = $warehouseService->getWarehouses([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('inventory::livewire.warehouses-list', [
            ...compact('columns', 'rows', 'activeWarehouse'),
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Warehouses'),
            ]);
    }
}
