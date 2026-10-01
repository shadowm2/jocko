<?php

namespace Modules\Dashboard\Models;

use App\Models\BaseModel;
use Exception;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\Dashboard\Database\Factories\CategoryFactory;
use Modules\Dashboard\Enums\CategoryType;
use Modules\Dashboard\Observers\CategoryObserver;

// use Modules\Dashboard\Database\Factories\CategoryFactory;
/**
 * @property int $id
 * @property int $depth
 * @property ?int $parent_id
 *
 * @extends BaseModel<Category>
 */
#[Fillable('name', 'slug', 'parent_id', 'description', 'is_active', 'type', 'icon', 'sort_order', 'depth')]
class Category extends BaseModel
{
    /**
     * @use HasFactory<CategoryFactory>
     */
    use HasFactory;

    protected static function booted(): void
    {
        Category::observe(CategoryObserver::class);
    }

    protected $casts = [
        'type' => CategoryType::class,
    ];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    /**
     * @return HasMany<Category, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /**
     * @return Collection<int, Category>
     *
     * @throws Exception
     */
    public function getParentsRecursive(): Collection
    {
        $parents = collect();

        $parent = $this->parent;

        $depth = 0;
        while ($parent) {
            $parents->prepend($parent);
            $parent = $parent->parent;
            $depth++;
            if ($depth > 50) {
                throw new Exception('WTF');
            }
        }

        return $parents;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getChildrenRecursive(): Collection
    {
        $allChildren = collect();
        /** @var Category $child */
        foreach ($this->children as $child) {
            $allChildren->push(...$child->getChildrenRecursive());
            $allChildren->push($child);
        }

        return $allChildren;
    }

    /**
     * @param  Collection<int, Category>  $categories
     * @return Collection<int, Category>
     */
    public static function flatten(
        Collection $categories,
        int $depth = 0
    ): Collection {
        /** @param Category $category */
        return $categories->flatMap(function (Category $category) use ($depth) {
            $category->depth = $depth;

            $result = collect([$category]);

            if ($category->children->isNotEmpty()) {
                $result = $result->merge(
                    self::flatten(
                        $category->children,
                        $depth + 1
                    )
                );
            }

            return $result;
        });
    }

    public function calculateDepth(): int
    {
        $depth = 0;
        $category = $this;

        while ($category->parent_id !== null) {
            $depth++;

            $category = $category->parent;

            if (! $category) {
                break;
            }
        }

        return $depth;
    }
}
