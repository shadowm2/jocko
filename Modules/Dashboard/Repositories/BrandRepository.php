<?php

namespace Modules\Dashboard\Repositories;

use App\Repositories\BaseRepository;
use Modules\Dashboard\Filters\BrandFilter;
use Modules\Dashboard\Repositories\Contracts\BrandRepositoryInterface;
use Modules\Inventory\Models\Brand;

/**
 * @extends BaseRepository<Brand>
 */
class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): BrandRepository
    {
        $this->query()
            ->with([
                'logo',
            ]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): BrandRepository
    {
        (new BrandFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
