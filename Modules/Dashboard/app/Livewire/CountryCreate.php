<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Dashboard\Livewire\Forms\CountryForm;

class CountryCreate extends Component
{
    public CountryForm $form;

    public function mount(): void {}

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('dashboard::messages.Country Created Successfully'), variant: 'success');
        $this->redirectRoute('countries.index', navigate: true);
    }

    public function render(): View
    {

        return view('dashboard::livewire.countries-create', [])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Country Create'),
            ]);
    }
}
