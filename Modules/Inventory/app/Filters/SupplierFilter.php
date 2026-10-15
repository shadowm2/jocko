<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Dashboard\Models\City;
use Modules\User\Models\User;

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
                    ->orWhereHas('city', fn (Builder $cityQuery) =>
                        $cityQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $cityId = $this->filters['city_id'] ?? '';
        if (is_numeric($cityId) && (int) $cityId > 0) {
            $this->builder->where('city_id', (int) $cityId);
        }

        if (array_key_exists('is_active', $this->filters)) {
            $activeValues = array_values(array_filter(
                (array) $this->filters['is_active'],
                static fn (mixed $value): bool => in_array((string) $value, ['0', '1'], true),
            ));

            if (count($activeValues) === 1) {
                $this->builder->where('is_active', (bool) $activeValues[0]);
            }
        }

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
