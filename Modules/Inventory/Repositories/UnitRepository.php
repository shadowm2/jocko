<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\UnitFilter;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Repositories\Contracts\UnitRepositoryInterface;

/**
 * @extends BaseRepository<Unit>
 */
class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
    public function __construct(Unit $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): UnitRepository
    {
        $this->query()
            ->with([
                'unitGroup',
            ]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): UnitRepository
    {
        (new UnitFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
