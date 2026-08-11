<?php

namespace Modules\Car\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Car\Models\Car;
use Modules\Car\Repositories\CarRepository;

/**
 * @extends BaseService<Car, CarRepository>
 */
class CarService extends BaseService
{
    public function __construct(
        CarRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Car>|Collection<int, Car>
     */
    public function getCars(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('name')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Car
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], Car::class);
        if (! isset($data['car_company_id']) && isset($data['car_company'])) {
            $data['car_company_id'] = resolve(CarCompanyService::class)->findBy($data['car_company'], 'slug')->id;
        }

        return parent::create($data);
    }

    public function update(int|Model $model, array $data): bool
    {
        if (! isset($data['car_company_id']) && isset($data['car_company'])) {
            $data['car_company_id'] = resolve(CarCompanyService::class)->findBy($data['car_company'], 'slug')->id;
        }

        return parent::update($model, $data);
    }
}
