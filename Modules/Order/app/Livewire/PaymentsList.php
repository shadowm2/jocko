<?php

namespace Modules\Order\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Order\Services\PaymentService;

class PaymentsList extends Component
{
    use WithPagination;

    public function render(PaymentService $paymentService): View
    {
        $columns = [
            ['label' => __('order::attributes.Order Number')],
            ['label' => __('order::attributes.User Name')],
            ['label' => __('order::attributes.Amount')],
            ['label' => __('order::attributes.Payment Method')],
            ['label' => __('order::attributes.Paid At')],
            [],
        ];

        return view('order::livewire.payments-list', [
            'rows' => $paymentService->getPayments(),
            'columns' => $columns,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('order::strings.Payments'),
            ]);
    }
}
