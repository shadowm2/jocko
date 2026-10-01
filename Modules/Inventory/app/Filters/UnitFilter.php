<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;

class UnitFilter extends BaseFilter
{
    protected function filter(): void
    {
        if (isset($this->filters['is_base'])) {
            $this->builder->where('is_base', $this->filters['is_base']);
        }
    }
}
