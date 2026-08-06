<?php

namespace Modules\Car\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Car\Models\CarCompany;
use Modules\Car\Repositories\CarCompanyRepository;

/**
 * @extends BaseService<CarCompany>
 */
class CarCompanyService extends BaseService
{
    public function __construct(
        CarCompanyRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @return LengthAwarePaginator<int, CarCompany>|Collection<int, CarCompany>
     */
    public function getCompanies(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $query = $this->repository
            ->withRelations();

        return $paginate ? $query->paginate() : $query->all();
    }

    public function create(array $data): CarCompany
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], CarCompany::class);

        return parent::create($data);
    }
}
