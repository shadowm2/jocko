<?php

namespace Modules\Order\Livewire;

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
            ['label' => __('order::attributes.Order Number')],
            ['label' => __('order::attributes.User Name')],
            ['label' => __('order::attributes.User Car')],
            ['label' => __('order::attributes.Warehouse')],
            ['label' => __('order::attributes.Items and Total')],
            ['label' => __('order::attributes.Order Status')],
            [],
        ];
        $orders = $orderService->getOrders([]);

        return view('order::livewire.orders-list', [
            'rows' => $orders,
            'columns' => $columns,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('order::strings.Orders'),
            ]);
    }
}
