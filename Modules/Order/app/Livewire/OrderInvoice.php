<?php

namespace Modules\Order\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Order\Models\Order;

class OrderInvoice extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load(['user', 'userCar.car', 'warehouse', 'items.item', 'items.unit', 'payments']);
    }

    public function render(): View
    {
        return view('order::livewire.order-invoice', [
            'total' => (float) $this->order->items->sum('total'),
            'paid' => (float) $this->order->payments->sum('amount'),
        ])
            ->layout('order::components.layouts.invoice')
            ->layoutData([
                'title' => __('order::strings.Invoice').' '.$this->order->order_number,
            ]);
    }
}
