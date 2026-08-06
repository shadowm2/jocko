<?php

namespace Modules\User\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;

/**
 * @extends BaseService<User, UserRepository>
 */
class UserService extends BaseService
{
    public function __construct(
        UserRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @return LengthAwarePaginator<int, User>|Collection<int, User>
     */
    public function getUsers(bool $paginate = true): LengthAwarePaginator|Collection
    {
        $query = $this->repository
            ->withRelations()
            ->orderBy('last_name');

        return $paginate ? $query->paginate() : $query->all();
    }

    public function create(array $data): Model
    {
        $data = $this->interpretData($data);
        $data['slug'] ??= Utils::generateUniqueSlug($data['first_name'].' '.$data['last_name'], User::class);

        return $this->repository->create($data);
    }

    public function update(int|Model $model, array $data): bool
    {
        if ($model instanceof User === false) {
            $model = $this->repository->find($model);
        }
        if (Utils::getBaseSlug($model->slug) !== Utils::generateBaseSlug($data['first_name'].' '.$data['last_name'], User::class)) {
            $data['slug'] = Utils::generateUniqueSlug($data['first_name'].' '.$data['last_name'], User::class);
        }

        $data = $this->interpretData($data);

        return $this->repository->update($model, $data);
    }

    protected function interpretData(array $data): array
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return parent::interpretData($data);
    }
}
