<?php

namespace Modules\Order\Filters;

use App\Filters\BaseFilter;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Modules\Order\Enums\PaymentMethod;

class PaymentFilter extends BaseFilter
{
    protected function filter(): void
    {
        $this->where(
            'order_id',
            $this->filters['order_id'] ?? null
        );

        $search = Utils::eDigits(trim((string) ($this->filters['search'] ?? '')));
        if ($search !== '') {
            $this->builder->whereHas('order', fn (Builder $order) => $order->matching($search));
        }

        if ($method = PaymentMethod::tryFrom((string) ($this->filters['method'] ?? ''))) {
            $this->builder->where('method', $method->value);
        }

        if ($from = Utils::jalaliToDate($this->filters['paid_from'] ?? null)) {
            $this->builder->whereDate('paid_at', '>=', $from);
        }

        if ($to = Utils::jalaliToDate($this->filters['paid_to'] ?? null)) {
            $this->builder->whereDate('paid_at', '<=', $to);
        }

        $this->builder->latest('paid_at')->latest('id');
    }
}
