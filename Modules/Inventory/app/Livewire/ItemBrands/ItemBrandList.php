<?php

namespace Modules\Inventory\Livewire\ItemBrands;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Services\ItemBrandService;

class ItemBrandList extends Component
{
    use WithPagination;

    public Item $item;

    public function toggleIsActive(string $itemBrandSlug): void
    {
        $itemBrandService = resolve(ItemBrandService::class);

        /** @var Item $item */
        $itemBrand = $itemBrandService->findByKey($itemBrandSlug);

        $itemBrandService->update($itemBrand, [
            'is_active' => ! $itemBrand->is_active,
        ]);

        Flux::toast(__('inventory::messages.Item Brand Updated Successfully'), variant: 'success');
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Item Brand Item'),
            ],
            [
                'label' => __('inventory::attributes.Item Brand Brand'),
            ],
            [
                'label' => __('inventory::attributes.Item Brand SKU'),
            ],
            [
                'label' => __('inventory::attributes.Item Brand Barcode'),
            ],
            [
                'label' => __('inventory::attributes.Item Brand Part Number'),
            ],
            [
                'label' => __('inventory::attributes.Item Brand Purchase Price'),
            ],
            [
                'label' => __('inventory::attributes.Item Brand Sale Price'),
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
            ],
            [
                'label' => __('strings.Actions'),
            ],
        ];
        $itemService = resolve(ItemBrandService::class);
        $filters = [
            'item_id' => $this->item->id,
        ];
        $rows = $itemService->getItemBrands($filters);

        return view('inventory::livewire.item-brands.item-brands-list', [
            ...compact('columns', 'rows'),
            'item' => $this->item,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Items'),
            ]);
    }
}
