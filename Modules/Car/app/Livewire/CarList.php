<?php

namespace Modules\Car\Livewire;

use App\Livewire\BaseComponent;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use Modules\Car\Services\CarService;

class CarList extends BaseComponent
{
    use WithPagination;

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
                'label' => __('car::attributes.Car Images'),
                'attrs' => function ($row) {
                    return [
                        'image' => $row->images()->first(),
                        'alt' => $row->name,
                        'initial' => mb_substr($row->name, 0, 1),
                        'name' => $row->name,
                    ];
                },
                'component' => 'dashboard::popup-image',
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
