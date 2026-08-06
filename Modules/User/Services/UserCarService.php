<?php

namespace Modules\User\Services;

use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Car\Models\UserCar;
use Modules\User\Repositories\UserCarRepository;

/**
 * @extends BaseService<UserCar, UserCarRepository>
 */
class UserCarService extends BaseService
{
    public function __construct(
        UserCarRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<mixed,string>  $filters
     * @return LengthAwarePaginator<int, UserCar>|Collection<int, UserCar>
     */
    public function getUserCars(?int $userId = null, array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $query = $this->repository
            ->orderBy('user_id')
            ->filtered($filters);
        if ($userId) {
            $query->forUser($userId);
        }

        return $paginate ? $query->paginate() : $query->get();
    }
}
