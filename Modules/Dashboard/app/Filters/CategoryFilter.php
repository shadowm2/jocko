<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Dashboard\Enums\CategoryType;

class CategoryFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $activeValues = array_values(array_filter((array) ($this->filters['is_active'] ?? []), static fn ($value) => in_array((string) $value, ['0', '1'], true)));
        if (count($activeValues) === 1) {
            $this->builder->where('is_active', (bool) $activeValues[0]);
        }

        if (isset($this->filters['type']) && $this->filters['type'] !== '') {
            if (! is_iterable($this->filters['type'])) {
                $typeFilters = [$this->filters['type']];
            } else {
                $typeFilters = $this->filters['type'];
            }
            $types = [];
            foreach ($typeFilters as $type) {
                if ($type instanceof CategoryType) {
                    $types[] = $type->value;
                } else {
                    $types[] = $type;
                }
            }
            $this->builder->whereIn('type', $types);
        }
    }
}
