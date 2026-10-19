<?php

namespace Modules\Order\Filters;

use App\Filters\BaseFilter;
use App\Helpers\Utils;
use Modules\Order\Enums\PaymentMethod;
use Modules\Order\Models\Order;
use Modules\Order\Models\Payment;

/** @extends BaseFilter<Payment> */
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
            $this->builder->whereIn('order_id', Order::query()->matching($search)->select('id'));
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
