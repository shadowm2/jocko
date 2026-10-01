<?php

namespace Modules\Dashboard\Livewire\Forms;

use Livewire\Form;
use Modules\Dashboard\Enums\CategoryIcon;
use Modules\Dashboard\Enums\CategoryType;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Services\CategoryService;

class CategoryForm extends Form
{
    public ?int $id = null;

    public string $name;

    public ?string $description;

    public ?string $parent = '';

    public ?string $type = CategoryType::Item->value;

    public ?string $icon;

    public ?bool $is_active = true;

    /**
     * @return array<string, mixed>
     */
    public function getRules(): array
    {
        $categoryService = resolve(CategoryService::class);
        $catTypes = implode(',', array_map(fn (CategoryType $cat) => $cat->value, CategoryType::cases()));
        $validIcons = CategoryIcon::values();
        $rules = [
            'name' => 'required|string',
            'parent' => ['nullable', 'string'],
            'type' => 'required|in:'.$catTypes,
            'description' => 'nullable|string',
            'icon' => 'required|string|in:'.implode(',', $validIcons),
            'is_active' => 'required|boolean',
        ];

        if ($this->parent && $this->id) {
            /** @var Category $cat */
            $cat = $categoryService->find($this->id);
            $children = $cat->getChildrenRecursive()->pluck('slug');
            if ($this->id) {
                $rules['parent'][] = 'not_in:'.$children->join(',');
            }
        }

        return $rules;
    }

    public function save(): void
    {
        $data = $this->validate();

        $categoryService = resolve(CategoryService::class);

        if ($this->id) {
            $categoryService->update($this->id, $data);
        } else {
            $categoryService->create($data);
        }
    }

    public function setCategory(Category $category): void
    {
        $this->id = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->icon = $category->icon;
        $this->parent = $category->parent?->slug;
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => __('dashboard::attributes.Category Name'),
            'type' => __('dashboard::attributes.Category Type'),
            'parent' => __('dashboard::attributes.Category Parent'),
            'icon' => __('dashboard::attributes.Category Icon'),
            'description' => __('dashboard::attributes.Category Description'),
            'is_active' => __('dashboard::attributes.Is Active'),
        ];
    }
}
