<?php

namespace Modules\Order\Livewire;

use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Livewire\Forms\PaymentForm;
use Modules\Order\Services\OrderService;

class PaymentCreate extends Component
{
    public PaymentForm $form;

    public function mount(): void
    {
        $this->form->setDefaults();
    }

    public function save(): void
    {
        $this->form->save();

        Flux::toast(__('order::messages.Payment Created Successfully'), variant: 'success');
        $this->redirectRoute('payments.index', navigate: true);
    }

    public function render(OrderService $orderService): View
    {
        // A cancelled order can't be paid; the sums let the select show what is still owed.
        $orders = $orderService->getOrders([
            'where' => [['status', '!=', OrderStatus::CANCELLED->value]],
        ], paginate: false);

        return view('order::livewire.payment-create', compact('orders'))
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('order::strings.Submit Payment'),
            ]);
    }
}
