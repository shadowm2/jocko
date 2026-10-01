<?php

namespace Modules\Dashboard\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Dashboard\Models\City;
use Modules\Dashboard\Models\Province;
use Modules\Dashboard\Repositories\CityRepository;

/**
 * @extends BaseService<City, CityRepository>
 */
class CityService extends BaseService
{
    public function __construct(
        CityRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, City>|Collection<int, City>
     */
    public function getCities(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('province_id')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Model
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], City::class);

        return parent::create($data);
    }

    public function interpretData(array $data): array
    {
        if (isset($data['province'])) {
            $provinceService = resolve(ProvinceService::class);
            /** @var Province $province */
            $province = $provinceService->findByKey($data['province']);
            $data['province_id'] = $province->id;
        }

        return parent::interpretData($data);
    }
}
