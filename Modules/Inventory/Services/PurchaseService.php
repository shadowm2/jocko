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
use Modules\Inventory\Models\PurchaseItem;
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
        // Never store a display-only status such as NEW.
        $data['status'] = $this->persistableStatus($data['status'] ?? null);

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

        if (array_key_exists('status', $data)) {
            $data['status'] = $this->persistableStatus($data['status']);
        }

        return DB::transaction(function () use ($model, $data, $items) {
            $updated = parent::update($model, $data);

            if (is_array($items)) {
                $this->syncItems($model, $items);
            }

            return $updated;
        });
    }

    /**
     * Reconcile a purchase's line items with the given lines.
     *
     * Each entry is keyed by warehouse item slug and carries its own
     * quantity and pricing. Lines that stay selected have their values
     * updated in place, lines dropped from the selection are soft
     * deleted, and a line re-selected after removal is restored rather
     * than duplicated.
     *
     * @param  array<string, array<string, mixed>>  $lines
     */
    protected function syncItems(Purchase $purchase, array $lines): void
    {
        $slugs = array_values(array_filter(array_keys($lines)));

        if (empty($slugs)) {
            $purchase->items()->delete();

            return;
        }

        $warehouseItems = resolve(WarehouseItemService::class)
            ->getWarehouseItems(['whereIn' => ['slug', $slugs]], paginate: false)
            ->keyBy('slug');

        foreach ($slugs as $slug) {
            if (! $warehouseItems->has($slug)) {
                throw new Exception("Warehouse item [{$slug}] not found.");
            }
        }

        $itemIds = $warehouseItems->pluck('item_id')->unique()->values()->all();

        $this->restoreItems($purchase, $itemIds);

        $purchase->items()
            ->whereNotIn('item_id', $itemIds)
            ->delete();

        $existing = $purchase->items()->get()->keyBy('item_id');

        foreach ($warehouseItems as $slug => $warehouseItem) {
            $values = $this->lineValues($lines[$slug] ?? []);

            $existingLine = $existing->get($warehouseItem->item_id);

            if ($existingLine) {
                $existingLine->update($values);

                continue;
            }

            $purchase->items()->create([
                'slug' => Utils::generateUniqueSlug(
                    'pi '.$purchase->slug.' '.$warehouseItem->slug,
                    PurchaseItem::class
                ),
                'item_id' => $warehouseItem->item_id,
                ...$values,
            ]);
        }
    }

    /**
     * Resolve an incoming status to one that is safe to store, rejecting
     * display-only states like NEW.
     */
    protected function persistableStatus(mixed $status): PurchaseStatus
    {
        if ($status instanceof PurchaseStatus) {
            return $status->isDisplayOnly() ? PurchaseStatus::getDefault() : $status;
        }

        if (is_string($status)) {
            return PurchaseStatus::tryFromStored($status);
        }

        return PurchaseStatus::getDefault();
    }

    /**
     * @param  array<string, mixed>  $line
     * @return array<string, float>
     */
    protected function lineValues(array $line): array
    {
        $quantity = (float) ($line['quantity'] ?? 1);
        $unitPrice = (float) ($line['unit_price'] ?? 0);
        $discount = (float) ($line['discount'] ?? 0);
        $tax = (float) ($line['tax'] ?? 0);

        return [
            'quantity' => $quantity,
            'received_quantity' => 0,
            'unit_price' => $unitPrice,
            'discount' => $discount,
            'tax' => $tax,
            // Total is derived from quantity x unit price.
            'total' => round($quantity * $unitPrice, 2),
        ];
    }

    /**
     * Bring back lines that were previously removed from this purchase
     * but are selected again, so editing the picker does not accumulate
     * duplicate rows for the same item.
     *
     * @param  array<int, int>  $itemIds
     */
    protected function restoreItems(Purchase $purchase, array $itemIds): void
    {
        if (empty($itemIds)) {
            return;
        }

        PurchaseItem::onlyTrashed()
            ->where('purchase_id', $purchase->id)
            ->whereIn('item_id', $itemIds)
            ->get()
            ->each->restore();
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
