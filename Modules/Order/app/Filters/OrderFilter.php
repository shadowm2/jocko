<?php

namespace Modules\Order\Filters;

use App\Filters\BaseFilter;
use App\Helpers\Utils;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Enums\PaymentStatus;
use Modules\Order\Models\Order;

/** @extends BaseFilter<Order> */
class OrderFilter extends BaseFilter
{
    protected function filter(): void
    {
        $this->where(
            'user_id',
            $this->filters['user_id'] ?? null
        );

        $search = Utils::eDigits(trim((string) ($this->filters['search'] ?? '')));
        if ($search !== '') {
            $this->builder->matching($search);
        }

        if ($status = OrderStatus::tryFrom((string) ($this->filters['status'] ?? ''))) {
            $this->builder->where('status', $status->value);
        }

        if ($paymentStatus = PaymentStatus::tryFrom((string) ($this->filters['payment_status'] ?? ''))) {
            $this->builder->paymentStatusIs($paymentStatus);
        }

        if ($from = Utils::jalaliToDate($this->filters['created_from'] ?? null)) {
            $this->builder->whereDate('created_at', '>=', $from);
        }

        if ($to = Utils::jalaliToDate($this->filters['created_to'] ?? null)) {
            $this->builder->whereDate('created_at', '<=', $to);
        }

        $this->builder->latest('id');
    }
}
