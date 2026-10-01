<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\StockMovementFilter;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Repositories\Contracts\StockMovementRepositoryInterface;

/**
 * @extends BaseRepository<StockMovement>
 */
class StockMovementRepository extends BaseRepository implements StockMovementRepositoryInterface
{
    public function __construct(StockMovement $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): StockMovementRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): StockMovementRepository
    {
        new StockMovementFilter($filters)
            ->apply($this->query());

        return $this;
    }
}
