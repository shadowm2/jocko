<?php

namespace Modules\Order\app\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Order\Services\OrderService;

class OrdersList extends Component
{
    use WithPagination;

    public function render(OrderService $orderService): View
    {
        $columns = [
            [
                'label' => __('order::attributes.User Name'),
            ],
            [
                'label' => __('order::attributes.User Car'),
            ],
            [],
        ];
        $orders = $orderService->getOrders([]);

        return view('order::livewire.orders-list', [
            'rows' => $orders,
            'columns' => $columns,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cars'),
            ]);
    }
}
