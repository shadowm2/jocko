<?php

namespace Modules\Car\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
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
            ->orderBy('name')
            ->withRelations();

        return $paginate ? $query->paginate() : $query->all();
    }

    public function create(array $data): CarCompany
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], CarCompany::class);

        $company = parent::create($data);
        $this->handleMedia($company, $data);

        return $company;
    }

    /**
     * @throws \Exception
     */
    public function update(Model|int $model, array $data): bool
    {
        if ($model instanceof CarCompany === false) {
            $model = self::find($model);
        }
        $data = $this->interpretData($data);
        $this->handleMedia($model, $data, imagesRelation: 'logo');

        return $this->repository->update($model, $data);
    }
}
