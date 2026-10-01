<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;

class BrandFilter extends BaseFilter
{
    protected function filter(): void
    {
        if (isset($this->filters['is_active'])) {
            $this->builder->where('is_active', $this->filters['is_active']);
        }
    }
}
