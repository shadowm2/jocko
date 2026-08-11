<?php

namespace Modules\Order\Repositories;

use App\Repositories\BaseRepository;
use Modules\Order\app\Filters\OrderFilter;
use Modules\Order\app\Models\Order;
use Modules\Order\Repositories\Contracts\OrderRepositoryInterface;

/**
 * @extends BaseRepository<Order>
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): OrderRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): OrderRepository
    {
        (new OrderFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
