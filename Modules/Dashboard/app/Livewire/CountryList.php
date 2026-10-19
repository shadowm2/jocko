<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CountryService;

class CountryList extends Component
{
    use WithPagination;
    use InteractsWithTableFilters;
    public array $filters = ['search' => '', 'is_active' => []];
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    protected function defaultTableFilters(): array { return ['search' => '', 'is_active' => []]; }
    private function filterDefinitions(): array { return [
        ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('dashboard::strings.Search Countries')],
        ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('dashboard::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
    ]; }
    private function sortOptions(): array { return ['name' => __('dashboard::attributes.Country Name'), 'code' => __('dashboard::attributes.Country Code'), 'is_active' => __('dashboard::attributes.Is Active')]; }

    public function toggleIsActive($countryCode): void
    {
        $countryService = resolve(CountryService::class);
        /** @var Country $country */
        $country = $countryService->findByKey($countryCode);
        $countryService->update($country, [
            'is_active' => ! $country->is_active,
        ]);
        Flux::toast(__('dashboard::messages.Country Updated Successfully'), variant: 'success');
        $this->resetPage();
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
        $rows = $countryService->getCountries([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('dashboard::livewire.countries-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(), 'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Countries'),
            ]);
    }
}
