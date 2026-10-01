<?php

namespace App\Repositories\Contracts;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use SortDirection;

/**
 * @template TModel of Model
 *
 * @mixin Builder<TModel>
 */
interface BaseRepositoryInterface
{
    /**
     * @param  array<string>  $columns
     * @return Collection<int, TModel>
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * @param  array<string>  $columns
     */
    public function find(int|string $id, array $columns = ['*']): ?Model;

    public function findBy(int|string $val, string $col): ?Model;

    public function findByKey(mixed $val): ?Model;

    /**
     * @param  array<string>  $columns
     */
    public function findOrFail(int|string $id, array $columns = ['*']): Model;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Model;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Model $model, array $data): bool;

    public function delete(Model $model): bool;

    /**
     * @return Builder<TModel>
     */
    public function query(): Builder;

    /**
     * @return BaseRepository<TModel>
     */
    public function withRelations(): BaseRepository;

    /**
     * @param  array<string>  $columns
     * @return LengthAwarePaginator<int, TModel>
     */
    public function paginate(
        int $perPage = 15,
        array $columns = ['*']
    ): LengthAwarePaginator;

    /**
     * @return BaseRepository<TModel>
     */
    public function orderBy(string $column = 'id', SortDirection $direction = SortDirection::Ascending): BaseRepository;

    public function exclude(int|array $ids = []): BaseRepository;
}
