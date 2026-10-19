<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Enums\StockMovementType;
use Modules\Inventory\Models\StockMovement;

/** @extends BaseFilter<StockMovement> */
class StockMovementFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->whereHas('item', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"));
        }

        $type = StockMovementType::tryFrom((string) ($this->filters['type'] ?? ''));
        if ($type) {
            $this->builder->where('type', $type->value);
        }

        // Either side of the movement counts, so a transfer shows in both warehouses.
        $warehouseId = $this->filters['warehouse_id'] ?? '';
        if (is_numeric($warehouseId) && (int) $warehouseId > 0) {
            $this->builder->where(fn (Builder $query) => $query
                ->where('from_warehouse_id', (int) $warehouseId)
                ->orWhere('to_warehouse_id', (int) $warehouseId));
        }

        $this->builder->latest()->latest('id');
    }
}
