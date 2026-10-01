<?php

namespace Modules\Inventory\Livewire\Brands;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Services\BrandService;
use Modules\Inventory\Models\Brand;

class BrandList extends Component
{
    use WithPagination;

    public function toggleIsActive(string $brandSlug): void
    {
        $brandService = resolve(BrandService::class);

        /** @var Brand $brand */
        $brand = $brandService->findByKey($brandSlug);

        $brandService->update($brand, [
            'is_active' => ! $brand->is_active,
        ]);

        Flux::toast(__('inventory::messages.Brand Updated Successfully'), variant: 'success');
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Supplier Name'),
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
        $rows = $brandService->getBrands();

        return view('inventory::livewire.brands-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Brands'),
            ]);
    }
}
