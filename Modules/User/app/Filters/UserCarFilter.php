<?php

namespace Modules\User\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class UserCarFilter extends BaseFilter
{
    /**
     * @param  array<string, mixed>  $filters
     */
    protected function filter(): void
    {
        $this->where(
            'user_id',
            $this->filters['user_id'] ?? null
        );

        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->whereHas('car', fn (Builder $car) => $car
                    ->where('name', 'like', "%{$search}%")
                    ->orWhereHas('company', fn (Builder $company) => $company->where('name', 'like', "%{$search}%")))
                    ->orWhereHas('color', fn (Builder $color) => $color->where('name', 'like', "%{$search}%"));
            });
        }
    }
}
