<?php

namespace Modules\Car\Filters;

use App\Filters\BaseFilter;
use Modules\Car\Models\CarCompany;

/** @extends BaseFilter<CarCompany> */
class CarCompanyFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where('name', 'like', "%{$search}%");
        }

        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'cars_count'], true)) {
            if ($sortBy === 'cars_count') {
                $this->builder->withCount('cars')->orderBy('cars_count', ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc');
            } else {
                $this->builder->orderBy('name', ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc');
            }
            $this->builder->orderBy('car_companies.id');
        }
    }
}
