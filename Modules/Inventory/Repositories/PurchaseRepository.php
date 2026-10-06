<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\PurchaseFilter;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Repositories\Contracts\PurchaseRepositoryInterface;

/**
 * @extends BaseRepository<Purchase>
 */
class PurchaseRepository extends BaseRepository implements PurchaseRepositoryInterface
{
    public function __construct(Purchase $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): PurchaseRepository
    {
        $this->query()
            ->with([])
            ->with('items.item')
            ->withCount('items')
            ->withSum('items', 'total');

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): PurchaseRepository
    {
        new PurchaseFilter($filters)
            ->apply($this->query());

        return $this;
    }
}
