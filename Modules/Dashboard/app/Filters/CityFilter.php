<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;

class CityFilter extends BaseFilter
{
    protected function filter(): void
    {
        if (isset($this->filters['is_active'])) {
            $this->builder->where('is_active', $this->filters['is_active']);
        }
        if (isset($this->filters['province'])) {
            $this->builder->whereHas('province', function ($query) {
                $query->where('slug', $this->filters['province']);
            });
        }
    }
}
