<?php

namespace Modules\Car\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Car\Models\CarCompany;

class CarFilter extends BaseFilter
{


    protected function filter(): void
    {
        $this->where(
            'car_company_id',
            $this->filters['car_company_id'] ?? null
        );

        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('company', fn (Builder $company) => $company->where('name', 'like', "%{$search}%"));
            });
        }

        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'company'], true)) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            if ($sortBy === 'company') {
                $this->builder->orderBy(CarCompany::query()->select('name')->whereColumn('car_companies.id', 'cars.car_company_id'), $direction);
            } else {
                $this->builder->orderBy('name', $direction);
            }
            $this->builder->orderBy('cars.id');
        }
    }
}
