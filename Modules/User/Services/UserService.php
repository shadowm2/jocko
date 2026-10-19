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
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, User>|Collection<int, User>
     */
    public function getUsers(array|bool $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        if (is_bool($filters)) {
            $paginate = $filters;
            $filters = [];
        }

        $this->repository->filtered($filters);
        if (! in_array($filters['sort_by'] ?? null, ['first_name', 'last_name', 'email', 'type'], true)) {
            $this->repository->orderBy('last_name')->orderBy('users.id');
        }
        $query = $this->repository->withRelations();

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
            $model = $this->findOrFail($model);
        }
        if (Utils::getBaseSlug($model->slug) !== Utils::generateBaseSlug($data['first_name'].' '.$data['last_name'])) {
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
