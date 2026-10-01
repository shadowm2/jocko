<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CityService;

class CityList extends Component
{
    use WithPagination;

    public function toggleIsActive($citySlug): void
    {
        $cityService = resolve(CityService::class);
        /** @var Country $country */
        $country = $cityService->findByKey($citySlug);
        $cityService->update($country, [
            'is_active' => ! $country->is_active,
        ]);
        Flux::toast(__('dashboard::messages.City Updated Successfully'), variant: 'success');
        $this->reset();
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('dashboard::attributes.City Name'),
                'component' => 'dashboard::country-name-cell',
            ],
            [
                'label' => __('dashboard::attributes.City Province'),
                'key' => 'province.name',
            ],
            [
                'label' => __('dashboard::attributes.Province Country'),
                'key' => 'province.country.name',
            ],
            [
                'label' => __('dashboard::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => '',
                'component' => 'dashboard::city-action-cell',
            ],
        ];
        $cityService = resolve(CityService::class);
        $rows = $cityService->getCities();

        return view('dashboard::livewire.cities-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cities'),
            ]);
    }
}
