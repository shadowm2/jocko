<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\WarehouseItemFilter;
use Modules\Inventory\Models\WarehouseItem;
use Modules\Inventory\Repositories\Contracts\WarehouseItemRepositoryInterface;

/**
 * @extends BaseRepository<WarehouseItem>
 */
class WarehouseItemRepository extends BaseRepository implements WarehouseItemRepositoryInterface
{
    public function __construct(WarehouseItem $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): WarehouseItemRepository
    {
        $this->query()
            ->with(['warehouse', 'item']);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): WarehouseItemRepository
    {
        new WarehouseItemFilter($filters)
            ->apply($this->query());

        return $this;
    }
}
