<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\UnitGroupFilter;
use Modules\Inventory\Models\UnitGroup;
use Modules\Inventory\Repositories\Contracts\UnitGroupRepositoryInterface;

/**
 * @extends BaseRepository<UnitGroup>
 */
class UnitGroupRepository extends BaseRepository implements UnitGroupRepositoryInterface
{
    public function __construct(UnitGroup $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): UnitGroupRepository
    {
        $this->query()
            ->with([])
            ->withCount(['units']);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): UnitGroupRepository
    {
        (new UnitGroupFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
