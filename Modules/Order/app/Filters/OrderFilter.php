<?php

namespace Modules\Order\Filters;

use App\Filters\BaseFilter;

class OrderFilter extends BaseFilter
{
    protected function filter(): void
    {
        $this->where(
            'user_id',
            $this->filters['user_id'] ?? null
        );

        $this->builder->latest('id');
    }
}
