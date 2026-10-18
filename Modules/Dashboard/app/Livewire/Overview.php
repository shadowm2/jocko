<?php

namespace Modules\Dashboard\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Inventory\Enums\PurchaseStatus;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\WarehouseItem;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;

class Overview extends Component
{
    private const LIST_SIZE = 5;

    public function render(): View
    {
        $lowStock = WarehouseItem::query()->lowStock();

        return view('dashboard::livewire.overview', [
            'orderCount' => Order::count(),
            'statusCounts' => Order::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            // Cancelled orders are not revenue.
            'orderValue' => OrderItem::whereRelation('order', 'status', '!=', OrderStatus::CANCELLED)->sum('total'),
            'openPurchases' => Purchase::whereIn('status', [PurchaseStatus::ORDERED, PurchaseStatus::PARTIALLY_RECEIVED])->count(),
            'customerCount' => User::where('type', UserType::Customer)->count(),
            'lowStockCount' => (clone $lowStock)->count(),
            'lowStock' => $lowStock->with(['item', 'warehouse'])
                ->orderByRaw('min_quantity - quantity desc')
                ->limit(self::LIST_SIZE)
                ->get(),
            'recentOrders' => Order::with(['user', 'userCar.car', 'warehouse'])
                ->withSum('items', 'total')
                ->latest('id')
                ->limit(self::LIST_SIZE)
                ->get(),
            'recentPurchases' => Purchase::with(['supplier.user', 'warehouse'])
                ->withSum('items', 'total')
                ->latest('id')
                ->limit(self::LIST_SIZE)
                ->get(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData(['title' => __('dashboard::strings.Dashboard')]);
    }
}
