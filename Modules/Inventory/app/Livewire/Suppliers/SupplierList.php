<?php

namespace Modules\Inventory\Livewire\Suppliers;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Services\CityService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Services\SupplierService;

class SupplierList extends Component
{
    use WithPagination;
    use InteractsWithTableFilters;

    /** @var array{search:string,city_id:string,is_active:array<int,string>} */
    public array $filters = [
        'search' => '',
        'city_id' => '',
        'is_active' => [],
    ];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    /** @var array<int, string> */
    #[Locked]
    public array $cityOptions = [];

    protected function defaultTableFilters(): array
    {
        return [
            'search' => '',
            'city_id' => '',
            'is_active' => [],
        ];
    }

    public function mount(CityService $cityService): void
    {
        $this->cityOptions = $cityService
            ->getCities(paginate: false)
            ->mapWithKeys(fn ($city) => [$city->id => $city->name])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('inventory::strings.Search Suppliers')],
            ['name' => 'city_id', 'type' => 'select', 'label' => __('dashboard::attributes.City'), 'placeholder' => __('strings.All'), 'options' => $this->cityOptions],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('inventory::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return [
            'name' => __('inventory::attributes.Supplier Name'),
            'city' => __('dashboard::attributes.City'),
            'phone' => __('inventory::attributes.Supplier Phone'),
            'is_active' => __('inventory::attributes.Is Active'),
        ];
    }

    public function toggleIsActive(string $supplierSlug): void
    {
        $supplierService = resolve(SupplierService::class);

        /** @var Supplier $supplier */
        $supplier = $supplierService->findByKey($supplierSlug);

        $supplierService->update($supplier, [
            'is_active' => ! $supplier->is_active,
        ]);

        $this->resetPage();
        Flux::toast(__('inventory::messages.Supplier Updated Successfully'), variant: 'success');
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Supplier Name'),
                'key' => 'user.first_name,user.last_name',
            ],
            [
                'label' => __('dashboard::attributes.City'),
                'key' => 'city.name',
            ],
            [
                'label' => __('inventory::attributes.Supplier Phone'),
                'key' => 'phone',
            ],
            [
                'label' => __('inventory::attributes.Supplier Address'),
                'key' => 'address',
                'maxlength' => 45,
            ],
            [
                'label' => __('inventory::attributes.Supplier Website'),
                'key' => 'website',
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => __('strings.Actions'),
                'component' => 'inventory::supplier-action-cell',
            ],
        ];
        $supplierService = resolve(SupplierService::class);
        $rows = $supplierService->getSuppliers([
            ...$this->filters,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ]);

        return view('inventory::livewire.suppliers-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Suppliers'),
            ]);
    }
}
