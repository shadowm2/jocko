<?php

namespace Modules\Car\Repositories;

use App\Repositories\BaseRepository;
use Modules\Car\Filters\CarFilter;
use Modules\Car\Models\Car;
use Modules\Car\Repositories\Contracts\CarRepositoryInterface;

/**
 * @extends BaseRepository<Car>
 */
class CarRepository extends BaseRepository implements CarRepositoryInterface
{
    public function __construct(Car $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): CarRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): CarRepository
    {
        (new CarFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
