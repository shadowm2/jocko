<?php

namespace Modules\Dashboard\Repositories;

use App\Repositories\BaseRepository;
use Modules\Dashboard\Filters\CityFilter;
use Modules\Dashboard\Models\City;
use Modules\Dashboard\Repositories\Contracts\CityRepositoryInterface;

/**
 * @extends BaseRepository<City>
 */
class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): CityRepository
    {
        $this->query()
            ->with([
                'province.country',
            ]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): CityRepository
    {
        (new CityFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
