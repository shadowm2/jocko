<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class UnitFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('symbol', 'like', "%{$search}%")
                    ->orWhereHas('unitGroup', fn (Builder $groupQuery) => $groupQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $unitGroupId = $this->filters['unit_group_id'] ?? '';
        if (is_numeric($unitGroupId) && (int) $unitGroupId > 0) {
            $this->builder->where('unit_group_id', (int) $unitGroupId);
        }

        foreach (['is_active', 'is_base'] as $field) {
            if (! array_key_exists($field, $this->filters)) {
                continue;
            }

            $values = array_values(array_filter(
                (array) $this->filters[$field],
                static fn (mixed $value): bool => in_array((string) $value, ['0', '1'], true),
            ));

            if (count($values) === 1) {
                $this->builder->where($field, (bool) $values[0]);
            }
        }

        $sortColumns = [
            'unit_group_id' => 'unit_group_id',
            'name' => 'name',
            'symbol' => 'symbol',
            'conversion_factor' => 'conversion_factor',
        ];
        $sortBy = $sortColumns[$this->filters['sort_by'] ?? ''] ?? null;
        if ($sortBy !== null) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            $this->builder->orderBy($sortBy, $direction);
            if ($sortBy === 'unit_group_id') {
                $this->builder->orderBy('conversion_factor', 'desc');
            }
            $this->builder->orderBy('id');
        }
    }
}
