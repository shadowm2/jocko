<?php

namespace Modules\Car\Livewire;

use App\Livewire\BaseComponent;
use Illuminate\Contracts\View\View;
use Modules\Car\Services\CarService;

class CarList extends BaseComponent
{
    public function render(CarService $carService): View
    {

        $columns = [
            [
                'label' => __('car::strings.Car Name'),
                'key' => 'name',
            ],
            [
                'label' => __('car::strings.Brand'),
                'key' => 'company.name',
            ],
            [
                'label' => '',
                'component' => 'car::car-action-cell',
            ],
        ];
        $cars = $carService->getCars();

        return view('car::livewire.cars-list', [
            ...compact('columns'),
            'rows' => $cars,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cars'),
            ]);
    }
}
