<?php

namespace Modules\Car\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Car\Models\Car;
use Modules\Car\Models\CarCompany;
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
        //        $this->repository->query()->where('slug', '=', 'fortwo');

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Car
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], Car::class);

        return parent::create($data);
    }

    public function update(Model|int $model, array $data): bool
    {
        if ($model instanceof Car === false) {
            $model = $this->find($model);
        }
        $this->customDir = $model->slug;

        return parent::update($model, $data);
    }

    protected function interpretData(array $data): array
    {
        if (! isset($data['car_company_id']) && isset($data['car_company'])) {
            /** @var CarCompany $carCompany */
            $carCompany = resolve(CarCompanyService::class)->findByKey($data['car_company']);
            $data['car_company_id'] = $carCompany->id;
        }

        return parent::interpretData($data);
    }
}
