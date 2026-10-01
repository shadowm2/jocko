<?php

namespace Modules\Inventory\Livewire\Items;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Dashboard\Services\CategoryService;
use Modules\Dashboard\Traits\RemoveFile;
use Modules\Inventory\Livewire\Forms\ItemForm;
use Modules\Inventory\Services\UnitGroupService;

class ItemCreate extends Component
{
    use RemoveFile;
    use WithFileUploads;

    public ItemForm $form;

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('inventory::messages.Item Created Successfully'), variant: 'success');
        $this->redirectRoute('items.index', navigate: true);
    }

    public function render(): View
    {
        $categoryService = resolve(CategoryService::class);
        $unitGroupService = resolve(UnitGroupService::class);
        $categories = $categoryService->getItemCategories();
        $unitGroups = $unitGroupService->getUnitGroups(paginate: false);

        return view('inventory::livewire.items-create', [
            ...compact('categories', 'unitGroups'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Item Add'),
            ]);
    }
}
