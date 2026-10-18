<?php

namespace Modules\Dashboard\Livewire;

use App\Helpers\Utils;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Models\Payment;

class Reports extends Component
{
    private const LIST_SIZE = 10;

    /** @var array{from:string,to:string} */
    public array $filters = [
        'from' => '',
        'to' => '',
    ];

    public function clearFilters(): void
    {
        $this->reset('filters');
    }

    public function render(): View
    {
        $from = Utils::jalaliToDate($this->filters['from']);
        $to = Utils::jalaliToDate($this->filters['to']);
        $within = fn (Builder $query, string $column): Builder => $query
            ->when($from, fn (Builder $query) => $query->whereDate($column, '>=', $from))
            ->when($to, fn (Builder $query) => $query->whereDate($column, '<=', $to));

        // Cancelled orders are not sales.
        $sale = fn (Builder $query): Builder => $within($query->where('status', '!=', OrderStatus::CANCELLED->value), 'created_at');
        $orderCount = $sale(Order::query())->count();
        $revenue = (float) OrderItem::query()->whereHas('order', $sale)->sum('total');

        // Whatever is still owed, whenever it was ordered; an overpaid order does not offset another.
        // ponytail: sums every open order in PHP, move to SQL when the order count gets large.
        $outstanding = Order::query()
            ->where('status', '!=', OrderStatus::CANCELLED->value)
            ->with('user')
            ->withSum('items', 'total')
            ->withSum('payments', 'amount')
            ->get()
            ->map(fn (Order $order): array => [
                'order' => $order,
                'balance' => round((float) $order->items_sum_total - (float) $order->payments_sum_amount, 2),
            ])
            ->filter(fn (array $row): bool => $row['balance'] > 0)
            ->sortByDesc('balance')
            ->values();

        return view('dashboard::livewire.reports', [
            'orderCount' => $orderCount,
            'revenue' => $revenue,
            'average' => $orderCount ? $revenue / $orderCount : 0,
            'collected' => (float) $within(Payment::query(), 'paid_at')->sum('amount'),
            'byMethod' => $within(Payment::query(), 'paid_at')
                ->selectRaw('method, sum(amount) as total, count(*) as payments')
                ->groupBy('method')
                ->orderByDesc('total')
                ->get(),
            // Quantities are left out on purpose: lines are sold in different units.
            'topItems' => OrderItem::query()
                ->whereHas('order', $sale)
                ->selectRaw('item_id, sum(total) as revenue, count(*) as orders')
                ->groupBy('item_id')
                ->orderByDesc('revenue')
                ->limit(self::LIST_SIZE)
                ->with('item')
                ->get(),
            'outstandingTotal' => $outstanding->sum('balance'),
            'outstandingCount' => $outstanding->count(),
            'outstanding' => $outstanding->take(self::LIST_SIZE),
            'filterDefinitions' => [
                ['name' => 'from', 'type' => 'date', 'label' => __('dashboard::strings.From Date')],
                ['name' => 'to', 'type' => 'date', 'label' => __('dashboard::strings.To Date')],
            ],
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData(['title' => __('dashboard::strings.Reports')]);
    }
}
