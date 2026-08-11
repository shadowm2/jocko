<?php

namespace Modules\User\Livewire\UserCar;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Car\Models\UserCar;
use Modules\Car\Services\CarCompanyService;
use Modules\Car\Services\CarService;
use Modules\User\Models\User;
use Modules\User\Services\UserCarService;

class UserCarEdit extends Component
{
    public User $user;

    public UserCar $userCar;

    /** @var array|string[] */
    public array $form = [
        //        'name' => '',
        'car_company_id' => '',
        'car_id' => '',
        'manufactured_at' => '',
    ];

    public function mount(User $user, UserCar $userCar): void
    {
        $this->user = $user;
        $this->userCar = $userCar;
        $this->resetForm($userCar);
    }

    public function resetForm($userCar): void
    {
        $this->form = [
            //            'name' => $userCar->car->name,
            'car_company_id' => $userCar->car->company->id,
            'car_id' => $userCar->car->id,
            'manufactured_at' => $userCar->manufactured_at_jalali,
            'description' => $userCar->description,
        ];
    }

    public function onSubmit(): void
    {
        $this->userCar->manufactured_at = $this->form['manufactured_at'];
        $this->userCar->update($this->form);

        Flux::toast(text: __('user::messages.Car Saved Successfully'), variant: 'success');
    }

    public function render(
        UserCarService $userCarService,
        CarCompanyService $carCompanyService,
        CarService $carService
    ): View {
        $user = Auth::user();
        $companies = $carCompanyService->getCompanies();
        $filters = [
            'car_company_id' => $this->form['car_company_id'],
        ];
        $cars = $carService->getCars($filters, false);

        return view('user::livewire.user-car-edit', [
            ...compact('companies', 'cars'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('user::strings.User :name Cars', ['name' => $user->last_name]),
            ]);
    }
}
