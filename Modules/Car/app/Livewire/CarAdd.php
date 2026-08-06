<?php

namespace Modules\Car\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Car\Livewire\Forms\CarForm;
use Modules\Car\Services\CarCompanyService;

class CarAdd extends Component
{
    public CarForm $form;

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('car::messages.Car Created Successfully'), variant: 'success');
        $this->redirectRoute('cars.index', navigate: true);
    }

    public function render(CarCompanyService $companyService): View
    {
        $companies = $companyService->getCompanies([], false);

        return view('car::livewire.car-add', [
            ...compact('companies'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cars'),
            ]);
    }
}
