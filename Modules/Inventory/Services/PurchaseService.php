<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

        return parent::create($data);
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
