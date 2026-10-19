<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Dashboard\Models\City;
use Modules\Inventory\Models\Supplier;
use Modules\User\Models\User;

/** @extends BaseFilter<Supplier> */
class SupplierFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search): void {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('city', fn (Builder $cityQuery) => $cityQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $cityId = $this->filters['city_id'] ?? '';
        if (is_numeric($cityId) && (int) $cityId > 0) {
            $this->builder->where('city_id', (int) $cityId);
        }

        $this->whereCheckboxes('is_active');

        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['name', 'city', 'phone', 'is_active'], true)) {
            $direction = ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
            match ($sortBy) {
                'city' => $this->builder->orderBy(
                    City::query()->select('name')->whereColumn('cities.id', 'suppliers.city_id'),
                    $direction,
                ),
                'phone' => $this->builder->orderBy('phone', $direction),
                'is_active' => $this->builder->orderBy('is_active', $direction),
                default => $this->builder
                    ->orderBy(User::query()->select('last_name')->whereColumn('users.id', 'suppliers.user_id'), $direction)
                    ->orderBy(User::query()->select('first_name')->whereColumn('users.id', 'suppliers.user_id'), $direction),
            };

            $this->builder->orderBy('suppliers.id');
        }

    }
}
