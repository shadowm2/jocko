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

        // Keep matching descendants visible in the tree even when their ancestors do not match filters.
        if (array_key_exists('sort_by', $filters)) {
            $allCategories = $categories->keyBy('id');
            $parentIds = $categories->pluck('parent_id')->filter()->diff($allCategories->keys());
            while ($parentIds->isNotEmpty()) {
                $parents = Category::query()->whereIn('id', $parentIds)->get();
                $allCategories = $allCategories->union($parents->keyBy('id'));
                $parentIds = $parents->pluck('parent_id')->filter()->diff($allCategories->keys());
            }
            $categories = $allCategories->values();
        }

        $sortDirection = ($filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc';
        $grouped = $categories->groupBy('parent_id')
            ->map(fn ($children) => ($filters['sort_by'] ?? 'hierarchy') === 'name'
                ? $children->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE, $sortDirection === 'desc')
                : $children->sortBy(fn ($category) => $category->depth === 0 ? $category->name : $category->getChildrenRecursive()->count()));
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
            $category = $categoryService->findByKeyOrFail($data['parent']);
            $data['parent_id'] = $category->id;
        } else {
            $data['parent_id'] = null;
        }

        return parent::interpretData($data);
    }

    /** @return LengthAwarePaginator<int, Category>|Collection<int, Category> */
    public function getItemCategories()
    {
        return $this->getCategories(
            filters: [
                'type' => [CategoryType::Item],
            ],
        );
    }
}
