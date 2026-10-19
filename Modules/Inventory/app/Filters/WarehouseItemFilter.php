<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\WarehouseItem;

/** @extends BaseFilter<WarehouseItem> */
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

        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->whereHas('item', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"));
        }

        $this->sort([
            'item' => Item::query()->select('name')->whereColumn('items.id', $this->builder->qualifyColumn('item_id')),
            'quantity' => 'quantity',
        ]);

        if ($this->filters['low_stock'] ?? false) {
            // Furthest below the minimum first.
            $this->builder->lowStock()->orderByRaw('min_quantity - quantity desc');
        }
    }
}
