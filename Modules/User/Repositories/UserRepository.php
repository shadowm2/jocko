<?php

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Modules\User\Models\User;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

/**
 * @extends BaseRepository<User>
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): UserRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }
}
