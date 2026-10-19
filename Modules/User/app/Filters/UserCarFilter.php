<?php

namespace Modules\User\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Car\Models\Car;
use Modules\Car\Models\CarCompany;
use Modules\Car\Models\Color;
use Modules\Car\Models\UserCar;

/** @extends BaseFilter<UserCar> */
class UserCarFilter extends BaseFilter
{
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

        $carId = $this->builder->qualifyColumn('car_id');
        $this->sort([
            'company' => CarCompany::query()->select('car_companies.name')
                ->join('cars', 'cars.car_company_id', '=', 'car_companies.id')
                ->whereColumn('cars.id', $carId),
            'car' => Car::query()->select('name')->whereColumn('cars.id', $carId),
            'color' => Color::query()->select('name')->whereColumn('colors.id', $this->builder->qualifyColumn('color_id')),
        ]);
    }
}
