<?php

namespace Modules\Dashboard\Filters;

use App\Filters\BaseFilter;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CountryService;

class ProvinceFilter extends BaseFilter
{
    protected function filter(): void
    {
        if (isset($this->filters['is_active'])) {
            $this->builder->where('is_active', $this->filters['is_active']);
        }
        if (isset($this->filters['country_id'])) {
            $this->builder->where('country_id', $this->filters['country_id']);
        }
        if (isset($this->filters['country'])) {
            /** @var Country $country */
            $country = resolve(CountryService::class)->findByKey($this->filters['country']);
            $this->builder->where('country_id', $country->id);
        }
    }
}
