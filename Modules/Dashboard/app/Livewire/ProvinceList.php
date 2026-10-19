<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Models\Province;
use Modules\Dashboard\Services\CountryService;
use Modules\Dashboard\Services\ProvinceService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;

class ProvinceList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    /** @var array<string, mixed> */
    public array $filters = ['search' => '', 'country_id' => '', 'is_active' => []];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    /** @var array<int|string, string> */
    #[Locked]
    public array $countryOptions = [];

    protected function defaultTableFilters(): array
    {
        return ['search' => '', 'country_id' => '', 'is_active' => []];
    }

    public function mount(CountryService $service): void
    {
        $this->countryOptions = $service->getCountries([], false)->mapWithKeys(fn ($c) => [$c->id => $c->name])->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('dashboard::strings.Search Provinces')],
            ['name' => 'country_id', 'type' => 'select', 'label' => __('dashboard::attributes.Province Country'), 'placeholder' => __('strings.All'), 'options' => $this->countryOptions],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('dashboard::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['name' => __('dashboard::attributes.Province Name'), 'country' => __('dashboard::attributes.Province Country'), 'is_active' => __('dashboard::attributes.Is Active')];
    }

    /** @param string $provinceSlug */
    public function toggleIsActive($provinceSlug): void
    {
        $provinceService = resolve(ProvinceService::class);
        /** @var Province $province */
        $province = $provinceService->findByKey($provinceSlug);
        $provinceService->update($province, [
            'is_active' => ! $province->is_active,
        ]);
        $this->resetPage();
        Flux::toast(__('dashboard::messages.Province Updated Successfully'), variant: 'success');
    }

    public function render(ProvinceService $provinceService): View
    {
        $columns = [
            [
                'label' => __('dashboard::attributes.Province Name'),
                'key' => 'name',
            ],
            [
                'label' => __('dashboard::attributes.Province Country'),
                'key' => 'country.name',
            ],
            [
                'label' => __('dashboard::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => '',
                'component' => 'dashboard::province-action-cell',
            ],
        ];
        $rows = $provinceService->getProvinces([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('dashboard::livewire.provinces-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(), 'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Provinces'),
            ]);
    }
}
