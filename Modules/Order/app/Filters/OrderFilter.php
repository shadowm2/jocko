<?php

namespace Modules\Order\app\Filters;

use App\Filters\BaseFilter;

class OrderFilter extends BaseFilter
{
    /**
     * @param  array<string, mixed>  $filters
     */


    protected function filter(): void
    {
        $this->where(
            'user_id',
            $this->filters['user_id'] ?? null
        );
    }
}
