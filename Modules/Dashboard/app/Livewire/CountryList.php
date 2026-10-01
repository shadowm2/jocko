<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CountryService;

class CountryList extends Component
{
    use WithPagination;

    public function toggleIsActive($countryCode): void
    {
        $countryService = resolve(CountryService::class);
        /** @var Country $country */
        $country = $countryService->findByKey($countryCode);
        $countryService->update($country, [
            'is_active' => ! $country->is_active,
        ]);
        Flux::toast(__('dashboard::messages.Country Updated Successfully'), variant: 'success');
        $this->reset();
    }

    public function render(CountryService $countryService): View
    {
        $columns = [
            [
                'label' => __('dashboard::attributes.Country Name'),
                'component' => 'dashboard::country-name-cell',
            ],
            [
                'label' => __('dashboard::attributes.Country Code'),
                'key' => 'code',
            ],
            [
                'label' => __('dashboard::attributes.Country Code3'),
                'key' => 'code3',
            ],
            [
                'label' => __('dashboard::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => '',
                'component' => 'dashboard::country-action-cell',
            ],
        ];
        $rows = $countryService->getCountries();

        return view('dashboard::livewire.countries-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Countries'),
            ]);
    }
}
