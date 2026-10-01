<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Models\UnitGroup;
use Modules\Inventory\Repositories\UnitRepository;

/**
 * @extends BaseService<Unit, UnitRepository>
 */
class UnitService extends BaseService
{
    public function __construct(
        UnitRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Unit>|Collection<int, Unit>
     */
    public function getUnits(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('unit_group_id')
            ->orderBy('conversion_factor', \SortDirection::Descending)
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function getBasicUnits(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $filters['is_base'] = true;

        return $this->getUnits($filters, $paginate);
    }

    public function create(array $data): Unit
    {
        $data = $this->interpretData($data);
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], Unit::class);

        return parent::create($data);
    }

    public function update(int|Model $model, array $data): bool
    {
        $data = $this->interpretData($data);

        return parent::update($model, $data);
    }

    protected function interpretData(array $data): array
    {
        $data['is_base'] ??= true;
        $data['is_active'] ??= true;
        if (isset($data['unit_group'])) {
            /** @var UnitGroup $unitGroup */
            $unitGroup = resolve(UnitGroupService::class)->findByKey($data['unit_group']);
            $data['unit_group_id'] = $unitGroup->id;
        }

        return parent::interpretData($data);
    }
}
