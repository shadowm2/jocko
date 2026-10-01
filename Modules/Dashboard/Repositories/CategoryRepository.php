<?php

namespace Modules\Dashboard\Repositories;

use App\Repositories\BaseRepository;
use Modules\Dashboard\Filters\CategoryFilter;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Repositories\Contracts\CategoryRepositoryInterface;

/**
 * @extends BaseRepository<Category>
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): CategoryRepository
    {
        $this->query()
            ->with(['parent']);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): CategoryRepository
    {
        (new CategoryFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
