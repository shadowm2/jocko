<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Enums\StockMovementType;
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
        $quantity = (float) ($data['quantity'] ?? 0);
        unset($data['quantity']);

        return DB::transaction(function () use ($data, $item, $warehouse, $quantity): WarehouseItem {
            $warehouseItem = $this->itemInWarehouse($item, $warehouse);
            if ($warehouseItem) {
                parent::update($warehouseItem, $data);
                $type = StockMovementType::ADJUSTMENT;
            } else {
                $data['slug'] ??= Utils::generateUniqueSlug('wi '.$data['warehouse'].' '.$data['item'], WarehouseItem::class);
                $warehouseItem = parent::create($data);
                $type = StockMovementType::INITIAL_STOCK;
            }

            resolve(StockMovementService::class)->adjust($warehouseItem, $quantity, $type);

            return $warehouseItem->refresh();
        });
    }

    public function update(int|Model $model, array $data): bool
    {
        unset($data['item']);
        unset($data['warehouse']);
        $data = $this->interpretData($data);

        // The form edits the quantity as a value; stock only changes through movements.
        $newQuantity = $data['quantity'] ?? null;
        unset($data['quantity']);

        return DB::transaction(function () use ($model, $data, $newQuantity): bool {
            $model = $model instanceof Model ? $model : $this->findOrFail($model);
            $updated = parent::update($model, $data);

            if ($newQuantity !== null) {
                resolve(StockMovementService::class)->adjust(
                    $model,
                    (float) $newQuantity - (float) $model->quantity,
                    StockMovementType::ADJUSTMENT,
                );
            }

            return $updated;
        });
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

    public function getItemsInWarehouse(Warehouse|string|null $warehouse, array $filters = [], $paginate = false): Collection|LengthAwarePaginator
    {
        if ($warehouse === null) {
            return $paginate ? new LengthAwarePaginator([], 0, 1) : new Collection;
        }
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
