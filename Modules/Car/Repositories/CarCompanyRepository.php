<?php

namespace Modules\Car\Repositories;

use App\Repositories\BaseRepository;
use Modules\Car\Filters\CarCompanyFilter;
use Modules\Car\Models\CarCompany;
use Modules\Car\Repositories\Contracts\CarCompanyRepositoryInterface;

/**
 * @extends BaseRepository<CarCompany>
 */
class CarCompanyRepository extends BaseRepository implements CarCompanyRepositoryInterface
{
    public function __construct(CarCompany $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): CarCompanyRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /** @param array<string, mixed> $filters */
    public function filtered(array $filters): CarCompanyRepository
    {
        (new CarCompanyFilter($filters))->apply($this->query());

        return $this;
    }
}
