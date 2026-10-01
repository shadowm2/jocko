<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;

class ItemBrandFilter extends BaseFilter
{
    protected function filter(): void
    {
        if (isset($this->filters['item_id'])) {
            $this->builder->where('item_id', $this->filters['item_id']);
        }
    }
}
