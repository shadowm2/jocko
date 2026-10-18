<?php

namespace Modules\Order\Livewire;

use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;
use Modules\Order\Livewire\Forms\PaymentForm;
use Modules\Order\Models\Payment;

class PaymentEdit extends Component
{
    public PaymentForm $form;

    public Payment $payment;

    public function mount(Payment $payment): void
    {
        $this->form->setPayment($payment);
    }

    public function save(): void
    {
        $this->form->save();

        Flux::toast(__('order::messages.Payment Updated Successfully'), variant: 'success');
        $this->redirectRoute('payments.index', navigate: true);
    }

    public function render(): View
    {
        return view('order::livewire.payment-edit')
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('order::strings.Edit Payment'),
            ]);
    }
}
