<?php

namespace Modules\Inventory\Livewire\Brands;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Services\BrandService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Models\Brand;

class BrandList extends Component
{
    use WithPagination;
    use InteractsWithTableFilters;

    /** @var array{search:string,is_active:array<int,string>} */
    public array $filters = [
        'search' => '',
        'is_active' => [],
    ];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    protected function defaultTableFilters(): array
    {
        return [
            'search' => '',
            'is_active' => [],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('inventory::strings.Search Brands')],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('inventory::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return [
            'name' => __('inventory::attributes.Brand Name'),
            'is_active' => __('inventory::attributes.Is Active'),
        ];
    }

    public function toggleIsActive(string $brandSlug): void
    {
        $brandService = resolve(BrandService::class);

        /** @var Brand $brand */
        $brand = $brandService->findByKey($brandSlug);

        $brandService->update($brand, [
            'is_active' => ! $brand->is_active,
        ]);

        $this->resetPage();
        Flux::toast(__('inventory::messages.Brand Updated Successfully'), variant: 'success');
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Brand Name'),
            ],
            [
                'label' => __('inventory::attributes.Brand Logo'),
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
            ],
            [
                'label' => __('strings.Actions'),
            ],
        ];

        $brandService = resolve(BrandService::class);
        $rows = $brandService->getBrands([
            ...$this->filters,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ]);

        return view('inventory::livewire.brands-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Brands'),
            ]);
    }
}
