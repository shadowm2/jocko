<?php

namespace Modules\Car\Livewire;

use App\Livewire\BaseComponent;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use Modules\Car\Services\CarCompanyService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;

class CompanyList extends BaseComponent
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    /** @var array<string, mixed> */
    public array $filters = ['search' => ''];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    protected function defaultTableFilters(): array
    {
        return ['search' => ''];
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('car::strings.Search Car Companies')]];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['name' => __('car::strings.Car Company Name'), 'cars_count' => __('car::strings.Cars Count')];
    }

    public function render(CarCompanyService $companyService): View
    {

        $columns = [
            [
                'label' => __('car::strings.Car Company Name'),
                'key' => 'name',
            ],
            [
                'label' => __('car::strings.Car Company Name'),
                'key' => 'slug',
            ],
            [
                'label' => __('car::strings.Car Company Name'),
                'attrs' => function ($row) {
                    return [
                        'image' => $row->logo,
                        'alt' => $row->name,
                        'initial' => mb_substr($row->name, 0, 1),
                        'name' => $row->name,
                    ];
                },
                'component' => 'dashboard::popup-image',
            ],
            [
                'label' => '',
                'component' => 'car::company-action-cell',
            ],
        ];
        $companies = $companyService->getCompanies([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('car::livewire.companies-list', [
            ...compact('columns'),
            'rows' => $companies,
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cars'),
            ]);
    }
}
