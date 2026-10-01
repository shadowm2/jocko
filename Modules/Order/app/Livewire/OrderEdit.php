<?php

namespace Modules\Order\app\Livewire;

use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;
use Modules\Order\app\Livewire\Forms\OrderForm;
use Modules\Order\app\Models\Order;
use Modules\User\Services\UserCarService;
use Modules\User\Services\UserService;

class OrderEdit extends Component
{
    public Order $order;

    public OrderForm $form;

    public function mount(Order $order): void
    {
        $this->form->setOrder($order);
    }

    public function save(): void
    {
        $this->form->save();

        Flux::toast(__('order::messages.Order Updated Successfully'), variant: 'success');
        $this->redirectRoute('orders.index', navigate: true);
    }

    public function render(
        UserCarService $userCarService,
        UserService $userService
    ): View {
        $users = $userService->getUsers(paginate: false);
        $userCars = $userCarService->getUserCars($this->form->user_id);

        return view('order::livewire.order-edit', [
            ...compact('userCars', 'users'),
        ])
            ->layout('dashboard::layouts.app');
    }
}
