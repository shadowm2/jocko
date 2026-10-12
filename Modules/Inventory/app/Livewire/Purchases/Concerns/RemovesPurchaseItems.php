<?php

namespace Modules\Inventory\Livewire\Purchases\Concerns;

use Livewire\Attributes\On;
use Modules\Inventory\Livewire\Forms\PurchaseForm;

trait RemovesPurchaseItems
{
    public PurchaseForm $form;

    public function removePurchaseItem(string $slug): void
    {
        $this->form->removePurchaseItem($slug);
    }

    #[On('warehouse-item-picker-updated')]
    public function itemPickerUpdated(): void
    {
        $this->form->syncSelectedItems();
    }
}
