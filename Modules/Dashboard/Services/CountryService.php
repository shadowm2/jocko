<?php

namespace Modules\Dashboard\Services;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Repositories\CountryRepository;

/**
 * @extends BaseService<Country, CountryRepository>
 */
class CountryService extends BaseService
{
    public function __construct(
        CountryRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Country>|Collection<int, Country>
     */
    public function getCountries(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->orderBy('is_active', \SortDirection::Descending)
            ->orderBy('name')
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Country
    {
        return parent::create($data);
    }

    public function update(int|Model $model, array $data): bool
    {
        $data = $this->interpretData($data);

        return parent::update($model, $data);
    }
}
