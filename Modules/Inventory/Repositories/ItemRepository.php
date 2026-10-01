<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\ItemFilter;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Repositories\Contracts\ItemRepositoryInterface;

/**
 * @extends BaseRepository<Item>
 */
class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): ItemRepository
    {
        $this->query()
            ->withCount('brands')
            ->with([
                'unitGroup',
            ]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): ItemRepository
    {
        (new ItemFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
