<?php

namespace Modules\Car\Filters;

use App\Filters\BaseFilter;

class CarFilter extends BaseFilter
{
    public function __construct(
        private readonly array $filters
    ) {}

    protected function filter(): void
    {
        $this->where(
            'car_company_id',
            $this->filters['car_company_id'] ?? null
        );

        if (! empty($this->filters['search'])) {
            $this->builder->whereHas(
                'carCompany',
                function ($query) {
                    $query->where(
                        'name',
                        'like',
                        '%'.$this->filters['search'].'%'
                    );
                }
            );
        }
    }
}
