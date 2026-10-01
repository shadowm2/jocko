<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;
use Modules\Dashboard\Enums\CategoryType;

class CategoryFilter extends BaseFilter
{
    protected function filter(): void
    {
        if (isset($this->filters['is_active'])) {
            $this->builder->where('is_active', $this->filters['is_active']);
        }

        if (isset($this->filters['type'])) {
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
