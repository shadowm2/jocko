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
     * @return ($paginate is true ? LengthAwarePaginator<int, Country> : Collection<int, Country>)
     */
    public function getCountries(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository->filtered($filters);
        if (! in_array($filters['sort_by'] ?? null, ['name', 'code', 'is_active'], true)) {
            $this->repository->orderBy('is_active', \SortDirection::Descending)->orderBy('name');
        }
        $this->repository->withRelations();

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
