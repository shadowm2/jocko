<?php

namespace Modules\Car\Livewire;

use App\Livewire\BaseComponent;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Modules\Car\Services\CarCompanyService;
use Modules\Car\Services\CarService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;

class CarList extends BaseComponent
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    /** @var array<string, mixed> */
    public array $filters = ['search' => '', 'car_company_id' => ''];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    /** @var array<int|string, string> */
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

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('car::strings.Search Cars')],
            ['name' => 'car_company_id', 'type' => 'select', 'label' => __('car::strings.Car Company'), 'placeholder' => __('strings.All'), 'options' => $this->companyOptions],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['name' => __('car::strings.Car Name'), 'company' => __('car::strings.Car Company')];
    }

    public function render(CarService $carService): View
    {

        $columns = [
            [
                'label' => __('car::strings.Car Name'),
            ],
            [
                'label' => __('car::strings.Brand'),
            ],
            [
                'label' => __('car::attributes.Car Images'),
            ],
            [
                'label' => '',
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
