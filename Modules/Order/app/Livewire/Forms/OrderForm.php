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

    public ?string $description = null;

    /** @var array<string, mixed> */
    protected array $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'user_car_id' => 'required|integer|exists:user_car,id',
        'description' => 'nullable|string',
    ];

    public function setOrder(Order $order): void
    {
        $this->order = $order;
        $this->user_id = $order->user_id;
        $this->user_car_id = $order->user_car_id;
        $this->description = $order->description;
    }

    public function save(): Order
    {
        $orderService = resolve(OrderService::class);
        $data = $this->validate();

        if (isset($this->order)) {
            // Update
            $orderService->update($this->order, $data);

            return $this->order;
        } else {
            // Create
            return $orderService->create($data);
        }
    }
}
