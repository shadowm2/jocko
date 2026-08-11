<?php

namespace Modules\Order\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Car\Models\UserCar;
use Modules\Order\app\Models\Order;
use Modules\Order\Repositories\OrderRepository;
use Modules\User\Models\User;
use Modules\User\Services\UserCarService;
use Modules\User\Services\UserService;

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

    public function create(array $data): Order
    {
        /** @var User $user */
        $user = resolve(UserService::class)->find($data['user_id']);
        /** @var UserCar $userCar */
        $userCar = resolve(UserCarService::class)->find($data['user_car_id']);

        $data['slug'] ??= Utils::generateUniqueSlug($user->initials().' '.$userCar->car?->name, Order::class);

        return $this->repository->create($data);
    }
}
