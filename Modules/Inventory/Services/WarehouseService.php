<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
     * @return ($paginate is true ? LengthAwarePaginator<int, Warehouse> : Collection<int, Warehouse>)
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
        $activeWarehouseId = Cache::get($cacheKey);
        if (is_int($activeWarehouseId)) {
            return Warehouse::find($activeWarehouseId);
        }

        $activeWarehouse = Warehouse::withCount('warehouseItems')
            ->orderByDesc('warehouse_items_count')
            ->orderBy('created_at')
            ->first();
        if ($activeWarehouse) {
            Cache::set($cacheKey, $activeWarehouse->id, 3600);
        }

        return $activeWarehouse;
    }

    public function setActiveWarehouse(Warehouse|string $warehouse): bool
    {
        if (is_string($warehouse)) {
            $warehouse = $this->findByKeyOrFail($warehouse);
        }
        $cacheKey = 'active_warehouses';

        return Cache::forever($cacheKey, $warehouse->id);

    }
}
