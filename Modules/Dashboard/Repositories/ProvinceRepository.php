<?php

namespace Modules\Dashboard\Repositories;

use App\Repositories\BaseRepository;
use Modules\Dashboard\Filters\ProvinceFilter;
use Modules\Dashboard\Models\Province;
use Modules\Dashboard\Repositories\Contracts\ProvinceRepositoryInterface;

/**
 * @extends BaseRepository<Province>
 */
class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    public function __construct(Province $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): ProvinceRepository
    {
        $this->query()
            ->with([
                'country',
            ]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): ProvinceRepository
    {
        (new ProvinceFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
