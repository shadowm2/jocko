<?php

namespace Modules\Order\Filters;

use App\Filters\BaseFilter;

class PaymentFilter extends BaseFilter
{
    protected function filter(): void
    {
        $this->where(
            'order_id',
            $this->filters['order_id'] ?? null
        );

        $this->builder->latest('paid_at')->latest('id');
    }
}
