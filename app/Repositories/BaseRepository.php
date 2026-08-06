<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use SortDirection;

/**
 * @template TModel of Model
 *
 * @implements BaseRepositoryInterface<TModel>
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var TModel
     */
    protected $model;

    /** @var Builder<TModel> */
    protected $query;

    /**
     * @param  TModel  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        /** @var Builder<TModel> $query */
        $query = $this->model->newQuery();
        $this->query = $query;
    }

    /**
     * @return Builder<TModel>
     */
    public function query(): Builder
    {
        return $this->query;
    }

    /**
     * @return Collection<int, TModel>
     */
    public function all(array $columns = ['*']): Collection
    {
        /** @var Collection<int, TModel> $data */
        $data = $this->query()
            ->get($columns);

        return $data;
    }

    public function find(
        int|string $id,
        array $columns = ['*']
    ): ?Model {
        return $this->query()
            ->find($id, $columns);
    }

    public function findOrFail(
        int|string $id,
        array $columns = ['*']
    ): Model {
        return $this->query()
            ->findOrFail($id, $columns);
    }

    public function findBy(int|string $val, string $col): Model
    {
        return $this->query()->where($col, $val)->first();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(
        Model $model,
        array $data
    ): bool {
        return $model->update($data);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Paginate models.
     *
     * @param  array<int, string>  $columns
     * @return LengthAwarePaginator<int, TModel>
     */
    public function paginate(
        int $perPage = 15,
        array $columns = ['*']
    ): LengthAwarePaginator {
        return $this->query()
            ->paginate(
                perPage: $perPage,
                columns: $columns
            );
    }

    /**
     * @return BaseRepository<TModel>
     */
    public function orderBy(string $column = 'id', SortDirection $direction = SortDirection::Ascending): BaseRepository
    {
        $this->query()->orderBy($column, $direction);

        return $this;
    }
}
