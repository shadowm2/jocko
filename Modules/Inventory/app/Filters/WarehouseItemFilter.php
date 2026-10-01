<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;

class WarehouseItemFilter extends BaseFilter
{
    protected function filter(): void
    {
        if ($this->hasFilter('warehouse_id')) {
            $this->builder->where('warehouse_id', $this->filters['warehouse_id']);
        }

        if ($this->hasFilter('item_id')) {
            $this->builder->where('item_id', $this->filters['item_id']);
        }
    }
}
