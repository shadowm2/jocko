<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Inventory\Models\UnitGroup;
use Modules\Inventory\Repositories\UnitGroupRepository;

/**
 * @extends BaseService<UnitGroup, UnitGroupRepository>
 */
class UnitGroupService extends BaseService
{
    public function __construct(
        UnitGroupRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, UnitGroup>|Collection<int, UnitGroup>
     */
    public function getUnitGroups(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('name')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): UnitGroup
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], UnitGroup::class);

        return parent::create($data);
    }

    public function update(int|Model $model, array $data): bool
    {
        return parent::update($model, $data);
    }
}
