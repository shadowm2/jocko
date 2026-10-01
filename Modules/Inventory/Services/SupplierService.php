<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Dashboard\Models\City;
use Modules\Dashboard\Services\CityService;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Repositories\SupplierRepository;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;
use Modules\User\Services\UserService;

/**
 * @extends BaseService<Supplier, SupplierRepository>
 */
class SupplierService extends BaseService
{
    public function __construct(
        SupplierRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Supplier>|Collection<int, Supplier>
     */
    public function getSuppliers(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy(
                User::select('last_name')
                    ->whereColumn('user_id', 'users.id')
            )
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Supplier
    {
        $data = $this->interpretData($data);
        $userService = resolve(UserService::class);
        $user = $userService->create($data);
        $data['user_id'] = $user->id;
        $data['slug'] ??= Utils::generateUniqueSlug('supplier '.$user->fullName(), Supplier::class);

        return parent::create($data);
    }

    protected function interpretData(array $data): array
    {
        $cityService = resolve(CityService::class);
        $data['type'] = UserType::Supplier->value;
        if (! empty($data['city'])) {
            /** @var City $city */
            $city = $cityService->findByKey($data['city']);
            $data['city_id'] = $city->id;
        }

        return parent::interpretData($data);
    }
}
