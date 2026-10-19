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

    /**
     * @param  array<string>  $columns
     * @return TModel|null
     */
    public function find(
        int|string $id,
        array $columns = ['*']
    ): ?Model {
        return $this->query()
            ->find($id, $columns);
    }

    /**
     * @param  array<string>  $columns
     * @return TModel
     */
    public function findOrFail(
        int|string $id,
        array $columns = ['*']
    ): Model {
        return $this->query()
            ->findOrFail($id, $columns);
    }

    /** @return TModel|null */
    public function findBy(int|string $val, string $col): ?Model
    {
        return $this->query()->where($col, $val)->first();
    }

    /** @return TModel|null */
    public function findByKey(mixed $val): ?Model
    {
        $col = $this->model->getRouteKeyName();

        return $this->query()->where($col, $val)->first();
    }

    /** @return TModel */
    public function findByKeyOrFail(mixed $val): Model
    {
        return $this->query()->where($this->model->getRouteKeyName(), $val)->firstOrFail();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function create(array $data): Model
    {
        $model = $this->model->newInstance($data);
        $model->save();

        return $model;
    }

    /**
     * @param  TModel  $model
     * @param  array<string, mixed>  $data
     */
    public function update(
        Model $model,
        array $data
    ): bool {
        return $model->update($data);
    }

    /** @param  TModel  $model */
    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
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
     * @param  string|Builder<*>  $column
     * @return BaseRepository<TModel>
     */
    public function orderBy(string|Builder $column = 'id', SortDirection $direction = SortDirection::Ascending): BaseRepository
    {
        if ($column instanceof Builder) {
            $this->query()->orderBy($column, $direction);
        } else {
            $this->query()->orderBy($column, $direction);
        }

        return $this;
    }

    /**
     * @param  int|array<int>  $ids
     * @return BaseRepository<TModel>
     */
    public function exclude(int|array $ids = []): BaseRepository
    {
        if (is_numeric($ids)) {
            $ids = [$ids];
        }

        $this->query()->whereNotIn('id', $ids);

        return $this;
    }

    /** @return TModel */
    public function getModel(): Model
    {
        return $this->model;
    }
}
