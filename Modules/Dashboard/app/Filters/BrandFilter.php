<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class BrandFilter extends BaseFilter
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
        if (in_array($sortBy, ['name', 'is_active'], true)) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            $this->builder
                ->orderBy($sortBy, $direction)
                ->orderBy('brands.id');
        }
    }
}
