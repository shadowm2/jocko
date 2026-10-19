<?php

namespace Modules\Car\Livewire;

use App\Livewire\BaseComponent;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Modules\Car\Services\CarCompanyService;
use Modules\Car\Services\CarService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;

class CarList extends BaseComponent
{
    use InteractsWithTableFilters;
    use WithPagination;

    public array $filters = ['search' => '', 'car_company_id' => ''];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    #[Locked]
    public array $companyOptions = [];

    protected function defaultTableFilters(): array
    {
        return ['search' => '', 'car_company_id' => ''];
    }

    public function mount(CarCompanyService $companyService): void
    {
        $this->companyOptions = $companyService->getCompanies([], false)->mapWithKeys(fn ($company) => [$company->id => $company->name])->all();
    }

    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('car::strings.Search Cars')],
            ['name' => 'car_company_id', 'type' => 'select', 'label' => __('car::strings.Car Company'), 'placeholder' => __('strings.All'), 'options' => $this->companyOptions],
        ];
    }

    private function sortOptions(): array
    {
        return ['name' => __('car::strings.Car Name'), 'company' => __('car::strings.Car Company')];
    }

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
        $cars = $carService->getCars([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('car::livewire.cars-list', [
            ...compact('columns'),
            'rows' => $cars,
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cars'),
            ]);
    }
}
