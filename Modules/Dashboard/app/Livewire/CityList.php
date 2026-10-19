<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CityService;
use Modules\Dashboard\Services\ProvinceService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;

class CityList extends Component
{
    use InteractsWithTableFilters;
    use WithPagination;

    public array $filters = ['search' => '', 'province_id' => '', 'is_active' => []];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    #[Locked]
    public array $provinceOptions = [];

    protected function defaultTableFilters(): array
    {
        return ['search' => '', 'province_id' => '', 'is_active' => []];
    }

    public function mount(ProvinceService $service): void
    {
        $this->provinceOptions = $service->getProvinces([], false)->mapWithKeys(fn ($p) => [$p->id => $p->name])->all();
    }

    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('dashboard::strings.Search Cities')],
            ['name' => 'province_id', 'type' => 'select', 'label' => __('dashboard::attributes.City Province'), 'placeholder' => __('strings.All'), 'options' => $this->provinceOptions],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('dashboard::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }

    private function sortOptions(): array
    {
        return ['name' => __('dashboard::attributes.City Name'), 'province' => __('dashboard::attributes.City Province'), 'is_active' => __('dashboard::attributes.Is Active')];
    }

    public function toggleIsActive($citySlug): void
    {
        $cityService = resolve(CityService::class);
        /** @var Country $country */
        $country = $cityService->findByKey($citySlug);
        $cityService->update($country, [
            'is_active' => ! $country->is_active,
        ]);
        Flux::toast(__('dashboard::messages.City Updated Successfully'), variant: 'success');
        $this->resetPage();
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
        $rows = $cityService->getCities([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('dashboard::livewire.cities-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(), 'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cities'),
            ]);
    }
}
