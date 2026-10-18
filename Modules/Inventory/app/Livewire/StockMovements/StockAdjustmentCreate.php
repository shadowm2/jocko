<?php

namespace Modules\Inventory\Livewire\StockMovements;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Inventory\Livewire\Forms\StockAdjustmentForm;
use Modules\Inventory\Services\WarehouseItemService;

class StockAdjustmentCreate extends Component
{
    public StockAdjustmentForm $form;

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('inventory::messages.Stock Adjustment Recorded Successfully'), variant: 'success');
        $this->redirectRoute('stock-movements.index', navigate: true);
    }

    public function render(): View
    {
        $warehouseItems = resolve(WarehouseItemService::class)->getWarehouseItems(paginate: false);

        return view('inventory::livewire.stock-adjustment-create', compact('warehouseItems'))
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Stock Adjustment'),
            ]);
    }
}
