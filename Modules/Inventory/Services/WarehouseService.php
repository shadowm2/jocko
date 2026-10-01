<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Repositories\WarehouseRepository;

/**
 * @extends BaseService<Warehouse, WarehouseRepository>
 */
class WarehouseService extends BaseService
{
    public function __construct(
        WarehouseRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Item>|Collection<int, Warehouse>
     */
    public function getWarehouses(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('warehouse_items_count', \SortDirection::Descending)
            ->orderBy('name')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Warehouse
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], Warehouse::class);

        return parent::create($data);
    }

    public function getActiveWarehouse(): ?Warehouse
    {
        $cacheKey = 'active_warehouses';
        if (Cache::has($cacheKey)) {
            $activeWarehouseId = Cache::get($cacheKey);

            return Warehouse::find($activeWarehouseId);
        } else {
            $activeWarehouse = Warehouse::withCount('items')
                ->orderByDesc('warehouse_items_count')
                ->orderBy('created_at')
                ->first();
            if ($activeWarehouse) {
                Cache::forever($cacheKey, $activeWarehouse->id);

                return $activeWarehouse;
            } else {
                return null;
            }
        }
    }

    public function setActiveWarehouse(Warehouse|string $warehouse): bool
    {
        if (is_string($warehouse)) {
            $warehouse = $this->findByKey($warehouse);
        }
        $cacheKey = 'active_warehouses';

        return Cache::forever($cacheKey, $warehouse->id);

    }
}
