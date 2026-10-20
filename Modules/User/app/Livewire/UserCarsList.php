<?php

namespace Modules\User\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;
use Modules\User\Models\User;
use Modules\User\Services\UserCarService;

class UserCarsList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
    ];

    /** @var array<string, mixed> */
    public array $filters = self::FILTERS;

    public string $sortBy = 'company';

    public string $sortDirection = 'asc';

    protected function defaultTableFilters(): array
    {
        return self::FILTERS;
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('user::strings.Search User Cars')],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['company' => __('car::strings.Car Company'), 'car' => __('car::strings.Car Name'), 'color' => __('car::strings.Car Color')];
    }

    public User $user;

    public function render(UserCarService $userCarService): View
    {

        $columns = [
            [
                'label' => __('car::strings.Car Company'),
            ],
            [
                'label' => __('car::strings.Car Name'),
            ],
            [
                'label' => __('car::strings.Car Color'),
            ],
            [
                'label' => '',
            ],
        ];
        $userCars = $userCarService->getUserCars($this->user->id, [...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('user::livewire.user-cars-list', [
            ...compact('columns'),
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
            'rows' => $userCars,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('user::strings.User :name Cars', ['name' => $this->user->last_name]),
            ]);
    }
}
