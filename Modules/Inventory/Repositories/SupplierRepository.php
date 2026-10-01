<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\BaseRepository;
use Modules\Inventory\Filters\SupplierFilter;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Repositories\Contracts\SupplierRepositoryInterface;

/**
 * @extends BaseRepository<Supplier>
 */
class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): SupplierRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): SupplierRepository
    {
        (new SupplierFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
