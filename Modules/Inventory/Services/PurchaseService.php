<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Enums\PurchaseStatus;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Repositories\PurchaseRepository;

/**
 * @extends BaseService<Purchase, PurchaseRepository>
 */
class PurchaseService extends BaseService
{
    public function __construct(
        PurchaseRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Purchase>|Collection<int, Purchase>
     */
    public function getPurchases(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Purchase
    {
        $supplier = resolve(SupplierService::class)->findByKey($data['supplier']);
        $data['supplier_id'] = $supplier->id;
        $slug = $data['warehouse'].' '.$supplier->user->fullName();
        $data['slug'] ??= Utils::generateUniqueSlug($slug, Purchase::class);
        $data['status'] = PurchaseStatus::getDefault();

        $items = $data['items'] ?? [];
        unset($data['items']);

        $purchase = DB::transaction(function () use ($data, $items) {
            $purchase = parent::create($data);
            $this->syncItems($purchase, $items);

            return $purchase;
        });

        return $purchase->refresh();
    }

    public function update(int|Model $model, array $data): bool
    {
        if ($model instanceof Model === false) {
            $model = $this->find($model);
        }

        $items = $data['items'] ?? null;
        unset($data['items']);

        return DB::transaction(function () use ($model, $data, $items) {
            $updated = parent::update($model, $data);

            if (is_array($items)) {
                $this->syncItems($model, $items);
            }

            return $updated;
        });
    }

    /**
     * Replace a purchase's line items with the given set.
     *
     * Accepts warehouse item slugs (what the picker binds) and resolves
     * each to its underlying item. Existing lines are soft deleted so the
     * previous quantities stay recoverable.
     *
     * @param  array<int, string>  $slugs
     */
    protected function syncItems(Purchase $purchase, array $slugs): void
    {
        $purchase->items()->delete();

        $slugs = array_values(array_unique(array_filter($slugs)));

        if (empty($slugs)) {
            return;
        }

        $warehouseItems = resolve(WarehouseItemService::class)
            ->getWarehouseItems(['whereIn' => ['slug', $slugs]], paginate: false)
            ->keyBy('slug');

        foreach ($slugs as $slug) {
            $warehouseItem = $warehouseItems->get($slug);

            if (! $warehouseItem) {
                throw new Exception("Warehouse item [{$slug}] not found.");
            }

            $purchase->items()->create([
                'item_id' => $warehouseItem->item_id,
                'quantity' => 1,
                'unit_price' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
            ]);
        }
    }

    protected function interpretData(array $data): array
    {
        $warehouseService = resolve(WarehouseService::class);
        $supplierService = resolve(SupplierService::class);

        if (! empty($data['supplier']) && empty($data['supplier_id'])) {
            $data['supplier_id'] = $supplierService->findByKey($data['supplier'])->id;
        }
        if (! empty($data['warehouse'])) {
            $data['warehouse_id'] = $warehouseService->findByKey($data['warehouse'])->id;
        }

        return parent::interpretData($data);
    }
}
