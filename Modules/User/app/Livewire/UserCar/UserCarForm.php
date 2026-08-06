<?php

namespace Modules\User\Livewire\UserCar;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Car\Models\UserCar;
use Modules\User\Models\User;
use Modules\User\Services\UserCarService;

class UserCarForm extends Component
{
    protected User $user;

    protected UserCar $userCar;

    public function mount(User $user, UserCar $userCar): void
    {
        $this->user = $user;
        $this->userCar = $userCar;
    }

    public function onEditClick(): void {}

    public function render(UserCarService $userCarService): View
    {
        dd($this->userCar, $this->user);
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
                'component' => 'user::user-car-form',
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
