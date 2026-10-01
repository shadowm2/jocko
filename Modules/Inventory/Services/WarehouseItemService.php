<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseItem;
use Modules\Inventory\Repositories\WarehouseItemRepository;

/**
 * @extends BaseService<WarehouseItem, WarehouseItemRepository>
 */
class WarehouseItemService extends BaseService
{
    public function __construct(
        WarehouseItemRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, WarehouseItem>|Collection<int, WarehouseItem>
     */
    public function getWarehouseItems(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): WarehouseItem
    {
        $data = $this->interpretData($data);
        $item = resolve(ItemService::class)->findByKey($data['item']);
        $warehouse = resolve(WarehouseService::class)->findByKey($data['warehouse']);
        $warehouseItem = $this->itemInWarehouse($item, $warehouse);
        if ($warehouseItem) {
            $data['quantity'] += $warehouseItem->quantity;

            parent::update($warehouseItem, $data);

            return $warehouseItem->refresh();
        } else {
            $data['slug'] ??= Utils::generateUniqueSlug('wi '.$data['warehouse'].' '.$data['item'], WarehouseItem::class);

            return parent::create($data);
        }

    }

    public function update(int|Model $model, array $data): bool
    {
        unset($data['item']);
        unset($data['warehouse']);
        $data = $this->interpretData($data);

        return parent::update($model, $data);
    }

    protected function interpretData(array $data): array
    {
        if (isset($data['item'])) {
            /** @var Item $item */
            $item = resolve(ItemService::class)->findByKey($data['item']);
            $data['item_id'] = $item->id;
        }

        if (isset($data['warehouse'])) {
            /** @var Warehouse $warehouse */
            $warehouse = resolve(WarehouseService::class)->findByKey($data['warehouse']);
            $data['warehouse_id'] = $warehouse->id;
        }

        if (($data['min_quantity_unlimited'] ?? false) === true) {
            $data['min_quantity'] = null;
        }
        if (($data['max_quantity_unlimited'] ?? false) === true) {
            $data['max_quantity'] = null;
        }

        return parent::interpretData($data);
    }

    public function getItemsInWarehouse(Warehouse|string $warehouse, array $filters = [], $paginate = false): Collection|LengthAwarePaginator
    {
        if (is_string($warehouse)) {
            $warehouse = resolve(WarehouseService::class)->findByKey($warehouse);
        }
        $filters['warehouse_id'] = $warehouse->id;

        return $this->getWarehouseItems(filters: $filters, paginate: $paginate);
    }

    public function itemInWarehouse(Item $item, Warehouse $warehouse): ?WarehouseItem
    {
        $filters = [];
        $filters['item_id'] = $item->id;

        return $this->getItemsInWarehouse($warehouse, $filters)->first();
    }
}
