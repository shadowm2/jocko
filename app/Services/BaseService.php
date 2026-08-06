<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @template TRepository of BaseRepository<TModel>
 */
abstract class BaseService
{
    /**
     * @var TRepository
     */
    protected BaseRepository $repository;

    /**
     * @param  TRepository  $repository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Collection<int, TModel>
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function find(int|string $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function findBy(int|string $val, string $col): ?Model
    {
        return $this->repository->findBy($val, $col);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    /**
     * @param  int|TModel  $model
     * @param  array<string, mixed>  $data
     */
    public function update(
        int|Model $model,
        array $data
    ): bool {
        if ($model instanceof Model === false) {
            $model = $this->find($model);
        }
        $data = $this->interpretData($data);

        return $this->repository->update($model, $data);
    }

    public function delete(Model $model): bool
    {
        return $this->repository->delete($model);
    }

    /** @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    protected function interpretData(array $data): array
    {
        return $data;
    }
}
