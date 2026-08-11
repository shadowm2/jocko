<?php

namespace Modules\User\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\User\Models\User;
use Modules\User\Services\UserCarService;

class UserCarsList extends Component
{
    protected User $user;

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function onEditClick(): void {}

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
        $userCars = $userCarService->getUserCars($this->user->id);

        return view('user::livewire.user-cars-list', [
            ...compact('columns'),
            'rows' => $userCars,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('user::strings.User :name Cars', ['name' => $this->user->last_name]),
            ]);
    }
}
