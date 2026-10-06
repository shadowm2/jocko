<?php

namespace Modules\Inventory\Livewire\Purchases;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Forms\PurchaseForm;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Services\PurchaseService;

class PurchaseList extends Component
{
    use WithPagination;

    public PurchaseForm $form;

    #[On('warehouse-items-selected')]
    public function handleItemsSelected(array $slugs): void
    {
        $this->form->selected = array_values($slugs);
        $this->form->syncSelectedItems();
    }

    public function editPurchaseItems(string $slug): void
    {
        $purchase = resolve(PurchaseService::class)->findByKey($slug);
        abort_unless($purchase instanceof Purchase, 404);

        $this->form->setPurchase($purchase);
        Flux::modal('purchase-items-editor')->show();
    }

    public function savePurchaseItems(): void
    {
        $this->form->saveItems();
        Flux::modal('purchase-items-editor')->close();
        Flux::toast(text: __('inventory::messages.Purchase Order Updated Successfully'), variant: 'success');
    }

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
                'label' => __('inventory::attributes.Purchase Items and Total'),
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
            'itemCatalog' => $this->form->selectedCatalog(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Purchases'),
            ]);
    }
}
