<?php

namespace Modules\Inventory\Livewire\Purchases;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Services\PurchaseService;

class PurchaseList extends Component
{
    use WithPagination;

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Purchase Supplier'),
            ],
            [
                'label' => __('inventory::attributes.Purchase Warehouse'),
            ],
            [
                'label' => __('inventory::attributes.Purchase Order Number'),
            ],
            [
                'label' => __('inventory::attributes.Purchase Status'),
            ],
            [
                'label' => __('inventory::attributes.Purchase Ordered At'),
            ],
            [
                'label' => __('inventory::attributes.Purchase Received At'),
            ],
            [
                'label' => '',
            ],
        ];
        $service = resolve(PurchaseService::class);
        $rows = $service->getPurchases();

        return view('inventory::livewire.purchases-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Purchases'),
            ]);
    }
}
