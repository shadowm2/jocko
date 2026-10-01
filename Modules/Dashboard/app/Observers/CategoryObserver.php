<?php

namespace Modules\Dashboard\Observers;

use Modules\Dashboard\Models\Category;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        $category->depth = $category->calculateDepth();
    }

    public function updating(Category $category): void
    {
        if (! $category->isDirty('parent_id')) {
            return;
        }

        $category->depth = $category->calculateDepth();
    }

    public function updated(Category $category): void
    {
        if ($category->wasChanged('parent_id')) {
            $this->updateChildrenDepths($category);
        }
    }

    private function updateChildrenDepths(Category $category): void
    {
        foreach ($category->children as $child) {
            $newDepth = $category->depth + 1;

            if ($child->depth !== $newDepth) {
                $child->updateQuietly([
                    'depth' => $newDepth,
                ]);
            }

            $this->updateChildrenDepths($child);
        }
    }
}
