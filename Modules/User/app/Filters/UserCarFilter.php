<?php

namespace Modules\User\Filters;

use App\Filters\BaseFilter;

class UserCarFilter extends BaseFilter
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(
        private readonly array $filters
    ) {}

    protected function filter(): void
    {
        $this->where(
            'user_id',
            $this->filters['user_id'] ?? null
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
