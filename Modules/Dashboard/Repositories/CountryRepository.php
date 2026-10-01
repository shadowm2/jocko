<?php

namespace Modules\Dashboard\Repositories;

use App\Repositories\BaseRepository;
use Modules\Dashboard\Filters\CountryFilter;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Repositories\Contracts\CountryRepositoryInterface;

/**
 * @extends BaseRepository<Country>
 */
class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    public function __construct(Country $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): CountryRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): CountryRepository
    {
        (new CountryFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
