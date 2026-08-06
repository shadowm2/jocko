<?php

namespace Modules\Order\Services;

use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Order\app\Models\Order;
use Modules\Order\Repositories\OrderRepository;

/**
 * @extends BaseService<Order, OrderRepository>
 */
class OrderService extends BaseService
{
    public function __construct(
        OrderRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Order>|Collection<int, Order>
     */
    public function getOrders(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }
}
