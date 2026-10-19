<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;

class UnitGroupFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where('name', 'like', "%{$search}%");
        }
    }
}
