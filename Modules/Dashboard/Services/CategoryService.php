<?php

namespace Modules\Dashboard\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Dashboard\Enums\CategoryType;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Repositories\CategoryRepository;

/**
 * @extends BaseService<Category, CategoryRepository>
 */
class CategoryService extends BaseService
{
    public function __construct(
        CategoryRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Category>|Collection<int, Category>
     */
    public function getCategories(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $categories = $this->repository
            ->filtered($filters)
            ->orderBy('parent_id')
            ->withRelations()
            ->all();

        $grouped = $categories->groupBy('parent_id')
            ->map(fn ($children) => $children->sortBy(
                fn ($category) => $category->depth === 0 ? $category->name : $category->getChildrenRecursive()->count()
            ));
        $result = collect();

        $walk = function ($parentId) use (
            &$walk,
            $grouped,
            &$result
        ) {
            foreach ($grouped->get($parentId ?? null, collect()) as $category) {
                $result->push($category);

                $walk($category->id);
            }
        };

        $walk(null);
        //        dd($result->pluck('depth', 'name')->toArray());

        return $result;

        //        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Model
    {
        $data['slug'] ??= Utils::generateUniqueSlug($data['name'], Category::class);

        return parent::create($data);
    }

    public function interpretData(array $data): array
    {
        $categoryService = resolve(CategoryService::class);
        if (! empty($data['parent'])) {
            $category = $categoryService->findByKey($data['parent']);
            $data['parent_id'] = $category->id;
        } else {
            $data['parent_id'] = null;
        }

        return parent::interpretData($data);
    }

    public function getItemCategories()
    {
        return $this->getCategories(
            filters: [
                'type' => [CategoryType::Item],
            ],
        );
    }
}
