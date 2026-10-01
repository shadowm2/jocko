<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\ItemBrandFilter;
use Modules\Inventory\Models\ItemBrand;
use Modules\Inventory\Repositories\Contracts\ItemBrandRepositoryInterface;

/**
 * @extends BaseRepository<ItemBrand>
 */
class ItemBrandRepository extends BaseRepository implements ItemBrandRepositoryInterface
{
    public function __construct(ItemBrand $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): ItemBrandRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): ItemBrandRepository
    {
        (new ItemBrandFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
