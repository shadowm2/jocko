<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Dashboard\Services\BrandService;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\ItemBrand;
use Modules\Inventory\Repositories\ItemBrandRepository;

/**
 * @extends BaseService<ItemBrand, ItemBrandRepository>
 */
class ItemBrandService extends BaseService
{
    public function __construct(
        ItemBrandRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, ItemBrand>|Collection<int, ItemBrand>
     */
    public function getItemBrands(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('item_id')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function createForItem(array $data, Item $item): ItemBrand
    {
        $brand = resolve(BrandService::class)->findByKey($data['brand']);
        $data['slug'] ??= Utils::generateUniqueSlug($item->name.' '.$brand->name, ItemBrand::class);
        $data['item_id'] = $item->id;

        return $this->create($data);
    }

    public function update(int|Model $model, array $data): bool
    {
        return parent::update($model, $data);
    }

    protected function interpretData(array $data): array
    {
        $brandService = resolve(BrandService::class);
        if (! empty($data['brand'])) {
            $brand = $brandService->findByKey($data['brand']);
            $data['brand_id'] = $brand->id;
        }

        return parent::interpretData($data);
    }
}
