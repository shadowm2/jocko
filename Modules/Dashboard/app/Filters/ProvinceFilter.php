<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CountryService;

class ProvinceFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('country', fn (Builder $country) => $country->where('name', 'like', "%{$search}%"));
            });
        }
        $this->whereCheckboxes('is_active');
        if (is_numeric($this->filters['country_id'] ?? null) && (int) $this->filters['country_id'] > 0) {
            $this->builder->where('country_id', $this->filters['country_id']);
        }
        if (isset($this->filters['country'])) {
            /** @var Country $country */
            $country = resolve(CountryService::class)->findByKey($this->filters['country']);
            $this->builder->where('country_id', $country->id);
        }
        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'country', 'is_active'], true)) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            if ($sortBy === 'country') {
                $this->builder->orderBy(Country::query()->select('name')->whereColumn('countries.id', 'provinces.country_id'), $direction);
            } else {
                $this->builder->orderBy($sortBy, $direction);
            }
            $this->builder->orderBy('provinces.id');
        }
    }
}
