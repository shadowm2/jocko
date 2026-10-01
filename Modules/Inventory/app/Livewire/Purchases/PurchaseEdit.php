<?php

namespace Modules\Inventory\Livewire\Purchases;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Inventory\Livewire\Forms\PurchaseForm;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Services\SupplierService;
use Modules\Inventory\Services\WarehouseService;

class PurchaseEdit extends Component
{
    public PurchaseForm $form;

    public Purchase $purchase;

    public function mount(): void
    {
        $this->form->setPurchase($this->purchase);
    }

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('inventory::messages.Purchase Updated Successfully'), variant: 'success');
        $this->redirectRoute('purchases.index', navigate: true);
    }

    public function render(): View
    {
        $warehouseService = resolve(WarehouseService::class);
        $supplierService = resolve(SupplierService::class);
        $warehouses = $warehouseService->getWarehouses(paginate: false);
        $suppliers = $supplierService->getSuppliers(paginate: false);

        return view('inventory::livewire.purchases-edit', [
            ...compact('warehouses', 'suppliers'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Units'),
            ]);
    }
}
