<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Enums\CategoryType;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Services\CategoryService;

class CategoryList extends Component
{
    use WithPagination;
    use InteractsWithTableFilters;

    public array $filters = ['search' => '', 'type' => '', 'is_active' => []];
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    protected function defaultTableFilters(): array { return ['search' => '', 'type' => '', 'is_active' => []]; }
    private function filterDefinitions(): array
    {
        $types = collect(CategoryType::cases())->mapWithKeys(fn ($type) => [$type->value => __('dashboard::strings.category_types.'.$type->value)])->all();
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('dashboard::strings.Search Categories')],
            ['name' => 'type', 'type' => 'select', 'label' => __('dashboard::attributes.Category Type'), 'placeholder' => __('strings.All'), 'options' => $types],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('dashboard::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }
    private function sortOptions(): array { return ['name' => __('dashboard::attributes.Category Name')]; }

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
        $this->resetPage();
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
        $rows = $categoryService->getCategories([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection], paginate: false);

        return view('dashboard::livewire.categories-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(), 'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Categories'),
            ]);
    }
}
