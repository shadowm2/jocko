<?php

namespace Modules\Inventory\Livewire\Brands;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Inventory\Livewire\Forms\BrandForm;

class BrandCreate extends Component
{
    use WithFileUploads;

    public BrandForm $form;

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('inventory::messages.Brand Created Successfully'), variant: 'success');
        $this->redirectRoute('brands.index', navigate: true);
    }

    public function render(): View
    {
        return view('inventory::livewire.brands-create', [])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Brand Add'),
            ]);
    }
}
