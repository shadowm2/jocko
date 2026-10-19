<?php

namespace Modules\User\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\User\Models\User;
use Modules\User\Services\UserCarService;

class UserCarsList extends Component
{
    use InteractsWithTableFilters;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
    ];

    /** @var array<string, mixed> */
    public array $filters = self::FILTERS;

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

    public User $user;

    public function render(UserCarService $userCarService): View
    {

        $columns = [
            [
                'label' => __('car::strings.Car Company'),
                'key' => 'car.company.name',
            ],
            [
                'label' => __('car::strings.Car Name'),
                'key' => 'car.name',
            ],
            [
                'label' => __('car::strings.Car Color'),
                'key' => 'color.name',
            ],
            [
                'label' => '',
                'component' => 'user::user-car-actions-cell',
            ],
        ];
        $userCars = $userCarService->getUserCars($this->user->id, $this->filters);

        return view('user::livewire.user-cars-list', [
            ...compact('columns'),
            'filterDefinitions' => $this->filterDefinitions(),
            'rows' => $userCars,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('user::strings.User :name Cars', ['name' => $this->user->last_name]),
            ]);
    }
}
