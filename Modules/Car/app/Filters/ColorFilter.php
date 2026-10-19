<?php

namespace Modules\Car\Filters;

use App\Filters\BaseFilter;
use Modules\Car\Models\Color;

/** @extends BaseFilter<Color> */
class ColorFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('hex', 'like', "%{$search}%");
            });
        }

        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'hex'], true)) {
            $this->builder
                ->orderBy($sortBy, ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc')
                ->orderBy('colors.id');
        }
    }
}
