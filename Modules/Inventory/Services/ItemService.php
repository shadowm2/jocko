<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Dashboard\Services\CategoryService;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Repositories\ItemRepository;

/**
 * @extends BaseService<Item, ItemRepository>
 */
class ItemService extends BaseService
{
    public function __construct(
        ItemRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Item>|Collection<int, Item>
     */
    public function getItems(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('name')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Item
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], Item::class);

        return parent::create($data);
    }

    protected function interpretData(array $data): array
    {
        $categoryService = resolve(CategoryService::class);
        $unitGroupService = resolve(UnitGroupService::class);

        if (! empty($data['category'])) {
            $data['category_id'] = $categoryService->findByKey($data['category'])->id;
        }
        if (! empty($data['unit_group'])) {
            $data['unit_group_id'] = $unitGroupService->findByKey($data['unit_group'])->id;
        }

        return parent::interpretData($data);
    }
}
