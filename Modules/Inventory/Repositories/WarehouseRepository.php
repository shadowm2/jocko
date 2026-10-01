<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\WarehouseFilter;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Repositories\Contracts\WarehouseRepositoryInterface;

/**
 * @extends BaseRepository<Warehouse>
 */
class WarehouseRepository extends BaseRepository implements WarehouseRepositoryInterface
{
    public function __construct(Warehouse $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): WarehouseRepository
    {
        $this->query()
            ->withCount('warehouseItems')
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): WarehouseRepository
    {
        (new WarehouseFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
