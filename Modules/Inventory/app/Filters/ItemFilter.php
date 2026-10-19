<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Dashboard\Models\Category;
use Modules\Inventory\Models\UnitGroup;

class ItemFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn (Builder $categoryQuery) =>
                        $categoryQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('unitGroup', fn (Builder $unitGroupQuery) =>
                        $unitGroupQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $categoryId = $this->filters['category_id'] ?? '';
        if (is_numeric($categoryId) && (int) $categoryId > 0) {
            $this->builder->where('category_id', (int) $categoryId);
        }

        if (array_key_exists('is_active', $this->filters)) {
            $activeValues = array_values(array_filter(
                (array) $this->filters['is_active'],
                static fn (mixed $value): bool => in_array((string) $value, ['0', '1'], true),
            ));

            if (count($activeValues) === 1) {
                $this->builder->where('is_active', (bool) $activeValues[0]);
            }
        }

        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'category', 'unit_group', 'is_active'], true)) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            match ($sortBy) {
                'category' => $this->builder->orderBy(
                    Category::query()->select('name')->whereColumn('categories.id', 'items.category_id'),
                    $direction,
                ),
                'unit_group' => $this->builder->orderBy(
                    UnitGroup::query()->select('name')->whereColumn('unit_groups.id', 'items.unit_group_id'),
                    $direction,
                ),
                'is_active' => $this->builder->orderBy('is_active', $direction),
                default => $this->builder->orderBy('name', $direction),
            };

            $this->builder->orderBy('items.id');
        }
    }
}
