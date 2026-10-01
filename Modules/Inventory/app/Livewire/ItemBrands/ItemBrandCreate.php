<?php

namespace Modules\Inventory\Livewire\ItemBrands;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Dashboard\Services\BrandService;
use Modules\Inventory\Livewire\Forms\ItemBrandForm;
use Modules\Inventory\Models\Item;

class ItemBrandCreate extends Component
{
    use WithFileUploads;

    public Item $item;

    public ItemBrandForm $form;

    public function mount(): void
    {
        $this->form->setItem($this->item);
    }

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('inventory::messages.Item Brand Created Successfully'), variant: 'success');
        $this->redirectRoute('items.brands.index', parameters: ['item' => $this->item], navigate: true);
    }

    public function render(): View
    {
        $brandService = resolve(BrandService::class);
        $brands = $brandService->getBrands(paginate: false);

        return view('inventory::livewire.item-brands.item-brands-create', [
            ...compact('brands'),
            'item' => $this->item,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Item Brand Add'),
            ]);
    }
}
