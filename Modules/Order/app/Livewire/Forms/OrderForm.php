<?php

namespace Modules\Order\app\Livewire\Forms;

use Livewire\Form;
use Modules\Order\app\Models\Order;
use Modules\Order\Services\OrderService;

class OrderForm extends Form
{
    public ?Order $order;

    public ?int $user_id = null;

    public ?int $user_car_id = null;

    /** @var array<string, mixed> */
    protected array $rules = [
        'user_id' => 'required',
    ];

    public function setOrder(Order $order): void
    {
        $this->order = $order;
        $this->user_id = $order->user_id;
        $this->user_car_id = $order->user_car_id;
    }

    public function save(): Order
    {
        $orderService = resolve(OrderService::class);
        $this->validate();

        if (isset($this->order)) {
            // Update
            $orderService->update($this->order, $this->all());

            return $this->order;
        } else {
            // Create
            return $orderService->update($this->order, $this->all());
        }
    }
}
