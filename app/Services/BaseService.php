<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Dashboard\Services\FileUploadService;

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

    protected ?string $customDir = null;

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

    public function findByKey(mixed $val): ?Model
    {
        return $this->repository->findByKey($val);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return TModel
     *
     * @throws Exception
     */
    public function create(array $data): Model
    {
        $data = $this->interpretData($data);

        $newModel = $this->repository->create($data);
        $this->handleMedia($newModel, $data);

        return $newModel;
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
        $this->handleMedia($model, $data);

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

    /**
     * @param  array<string, mixed>  $data
     *
     * @throws Exception
     */
    public function handleMedia(Model $model, array $data, string $imagesRelation = 'images'): void
    {
        $class = get_class($model);
        $moduleName = explode('\\', $class);

        // Typically: Modules\{ModuleName}\Entities\ModelName
        if ($moduleName[0] === 'Modules') {
            /** @var string $moduleName */
            $moduleName = $moduleName[1] ?? null;
        } else {
            throw new Exception('Module name must be a valid module name');
        }

        if (! empty($data['deleted_images'])) {
            if (! is_array($data['deleted_images'])) {
                throw new Exception('deleted_images must be an array');
            }
            $deletedImages = $model->$imagesRelation()->whereIn('id', $data['deleted_images'])->get();
            $deletedImages->each(function ($delImage) {
                $delImage->deleteFile();
                $delImage->delete();
            });
        }

        if (! empty($data['images'])) {
            if (! is_array($data['images'])) {
                throw new Exception('images must be an array');
            }
            //            dd($data);
            foreach ($data['images'] as $image) {
                resolve(FileUploadService::class)->upload(
                    file: $image,
                    module: $moduleName,
                    model: $model,
                    disk: 'public',
                    customDir: $this->customDir
                );
            }
        }
    }
}
