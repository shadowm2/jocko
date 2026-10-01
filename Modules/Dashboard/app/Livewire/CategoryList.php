<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CategoryService;

class CategoryList extends Component
{
    use WithPagination;

    public ?Category $selectedCategory = null;

    public function toggleIsActive($categorySlug): void
    {
        $categoryService = resolve(CategoryService::class);
        /** @var Country $country */
        $country = $categoryService->findByKey($categorySlug);
        $categoryService->update($country, [
            'is_active' => ! $country->is_active,
        ]);
        Flux::toast(__('dashboard::messages.Category Updated Successfully'), variant: 'success');
        $this->reset();
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('dashboard::attributes.Category Name'),
                'component' => 'dashboard::category-name-cell',
            ],
            [
                'label' => __('dashboard::attributes.Category Type'),
                'render' => fn ($row) => __('dashboard::strings.category_types.'.$row->type->name),
            ],
            [
                'label' => __('dashboard::attributes.Category Parent'),
                'key' => 'parent.name',
            ],
            [
                'label' => __('dashboard::attributes.Category Icon'),
                'component' => 'dashboard::category-icon-cell',
            ],
            [
                'label' => __('dashboard::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => '',
                'component' => 'dashboard::category-action-cell',
            ],
        ];
        $categoryService = resolve(CategoryService::class);
        $rows = $categoryService->getCategories(paginate: false);

        return view('dashboard::livewire.categories-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Categories'),
            ]);
    }
}
