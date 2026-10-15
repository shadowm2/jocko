<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Dashboard\Models\Province;

class CityFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('province', fn (Builder $province) => $province->where('name', 'like', "%{$search}%"));
            });
        }
        $activeValues = array_values(array_filter((array) ($this->filters['is_active'] ?? []), static fn ($value) => in_array((string) $value, ['0', '1'], true)));
        if (count($activeValues) === 1) {
            $this->builder->where('is_active', (bool) $activeValues[0]);
        }
        if (isset($this->filters['province']) && is_string($this->filters['province']) && $this->filters['province'] !== '') {
            $this->builder->whereHas('province', function ($query) {
                $query->where('slug', $this->filters['province']);
            });
        }
        if (is_numeric($this->filters['province_id'] ?? null) && (int) $this->filters['province_id'] > 0) {
            $this->builder->where('province_id', (int) $this->filters['province_id']);
        }
        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'province', 'is_active'], true)) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            if ($sortBy === 'province') {
                $this->builder->orderBy(Province::query()->select('name')->whereColumn('provinces.id', 'cities.province_id'), $direction);
            } else {
                $this->builder->orderBy($sortBy, $direction);
            }
            $this->builder->orderBy('cities.id');
        }
    }
}
