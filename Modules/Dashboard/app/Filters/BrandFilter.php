<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Models\Brand;

/** @extends BaseFilter<Brand> */
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

        $this->whereCheckboxes('is_active');

        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'is_active'], true)) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            $this->builder
                ->orderBy($sortBy, $direction)
                ->orderBy('brands.id');
        }
    }
}
