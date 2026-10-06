<?php

namespace Modules\Inventory\Livewire\Purchases;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Inventory\Livewire\Forms\PurchaseForm;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Services\SupplierService;

class PurchaseEdit extends Component
{
    public PurchaseForm $form;

    public Purchase $purchase;

    #[On('warehouse-selected')]
    public function handleWarehouseSelected(?string $warehouse): void
    {
        $this->form->warehouse = $warehouse ?? '';
    }

    #[On('warehouse-items-selected')]
    public function handleItemsSelected(array $slugs): void
    {
        $this->form->selected = array_values($slugs);
    }

    public function mount(): void
    {
        $this->form->setPurchase($this->purchase);
    }

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('inventory::messages.Purchase Order Updated Successfully'), variant: 'success');
        $this->redirectRoute('purchases.index', navigate: true);
    }

    public function render(): View
    {
        $supplierService = resolve(SupplierService::class);
        $suppliers = $supplierService->getSuppliers(paginate: false);

        return view('inventory::livewire.purchases-edit', [
            ...compact('suppliers'),
            'itemCatalog' => $this->form->selectedCatalog(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Purchase Edit'),
            ]);
    }
}
