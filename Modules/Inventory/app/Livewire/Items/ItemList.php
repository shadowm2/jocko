<?php

namespace Modules\Inventory\Livewire\Items;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Services\ItemService;

class ItemList extends Component
{
    use WithPagination;

    public function toggleIsActive(string $itemSlug): void
    {
        $itemService = resolve(ItemService::class);

        /** @var Item $item */
        $item = $itemService->findByKey($itemSlug);

        $itemService->update($item, [
            'is_active' => ! $item->is_active,
        ]);

        Flux::toast(__('inventory::messages.Item Updated Successfully'), variant: 'success');
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Item Image'),
            ],
            [
                'label' => __('inventory::attributes.Item Name'),
            ],
            [
                'label' => __('inventory::attributes.Item Category'),
            ],
            [
                'label' => __('inventory::attributes.Item Unit Group'),
            ],
            [
                'label' => __('inventory::attributes.Item Brands Count'),
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
            ],
            [
                'label' => __('strings.Actions'),
            ],
        ];
        $itemService = resolve(ItemService::class);
        $rows = $itemService->getItems();

        return view('inventory::livewire.items-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Items'),
            ]);
    }
}
