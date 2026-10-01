<?php

namespace Modules\Dashboard\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Dashboard\Repositories\BrandRepository;
use Modules\Inventory\Models\Brand;

/**
 * @extends BaseService<Brand, BrandRepository>
 */
class BrandService extends BaseService
{
    public function __construct(
        BrandRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Brand>|Collection<int, Brand>
     */
    public function getBrands(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('name')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    /**
     * @throws Exception
     */
    public function create(array $data): Brand
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], Brand::class);

        $brand = parent::create($data);
        $this->handleMedia($brand, $data);

        return $brand;
    }

    /**
     * @throws Exception
     */
    public function update(Model|int $model, array $data): bool
    {
        if ($model instanceof Model === false) {
            $model = $this->findOrFail($model);
        }
        $this->handleMedia($model, $data);
        $data = $this->interpretData($data);

        return $this->repository->update($model, $data);
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @throws Exception
     */
    public function handleMedia(Model $model, array $data, string $imagesRelation = 'images'): void
    {
        parent::handleMedia($model, $data, 'logo');
    }
}
