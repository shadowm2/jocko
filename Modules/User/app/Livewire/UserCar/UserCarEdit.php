<?php

namespace Modules\User\Livewire\UserCar;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Car\Models\UserCar;
use Modules\Car\Services\CarCompanyService;
use Modules\Car\Services\CarService;
use Modules\User\Livewire\Forms\UserCarForm;
use Modules\User\Models\User;

class UserCarEdit extends Component
{
    public UserCarForm $form;

    public User $user;

    public UserCar $userCar;

    public function mount(): void
    {
        $this->form->setUserCar($this->userCar);
    }

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('user::messages.Car Saved Successfully'), variant: 'success');
        $this->redirectRoute('users.cars.index', ['user' => $this->user], navigate: true);
    }

    public function render(
        CarCompanyService $carCompanyService,
        CarService $carService
    ): View {
        $user = Auth::user();
        $companies = $carCompanyService->getCompanies();
        $filters = [
            'car_company_id' => $this->userCar->car_company_id,
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
