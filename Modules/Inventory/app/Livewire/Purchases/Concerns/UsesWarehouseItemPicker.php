<?php

namespace Modules\Inventory\Livewire\Purchases\Concerns;

use Livewire\Attributes\On;
use Modules\Inventory\Livewire\Forms\PurchaseForm;

trait UsesWarehouseItemPicker
{
    public PurchaseForm $form;

    #[On('warehouse-selected')]
    public function handleWarehouseSelected(?string $warehouse): void
    {
        $this->form->warehouse = $warehouse ?? '';
    }

    /** @param array<int, string> $slugs */
    #[On('warehouse-items-selected')]
    public function handleItemsSelected(array $slugs): void
    {
        $this->form->selected = array_values($slugs);
        $this->form->syncSelectedItems();
    }
}
