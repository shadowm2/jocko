<?php

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Modules\Car\Models\UserCar;
use Modules\User\Filters\UserCarFilter;
use Modules\User\Repositories\Contracts\UserCarRepositoryInterface;

/**
 * @extends BaseRepository<UserCar>
 */
class UserCarRepository extends BaseRepository implements UserCarRepositoryInterface
{
    public function __construct(UserCar $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): UserCarRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @return Builder<UserCar>
     */
    public function forUser(int $userId): Builder
    {
        return $this->query()
            ->where('user_id', $userId)
            ->with([
                'car.company',
                'color',
            ]);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): UserCarRepository
    {
        (new UserCarFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
