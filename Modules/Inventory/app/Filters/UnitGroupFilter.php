<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Modules\Inventory\Models\UnitGroup;

/** @extends BaseFilter<UnitGroup> */
class UnitGroupFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where('name', 'like', "%{$search}%");
        }

        $this->sort(['name' => 'name', 'units_count' => 'units_count']);
    }
}
