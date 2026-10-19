<?php

namespace Modules\Inventory\Livewire\Items;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Services\CategoryService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Services\ItemService;

class ItemList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    /** @var array<string, mixed> */
    public array $filters = [
        'search' => '',
        'category_id' => '',
        'is_active' => [],
    ];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    /** @var array<int, string> */
    #[Locked]
    public array $categoryOptions = [];

    protected function defaultTableFilters(): array
    {
        return [
            'search' => '',
            'category_id' => '',
            'is_active' => [],
        ];
    }

    public function mount(CategoryService $categoryService): void
    {
        $this->categoryOptions = $categoryService
            ->getItemCategories()
            ->mapWithKeys(fn ($category) => [$category->id => $category->name])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('inventory::strings.Search Items')],
            ['name' => 'category_id', 'type' => 'select', 'label' => __('inventory::attributes.Item Category'), 'placeholder' => __('strings.All'), 'options' => $this->categoryOptions],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('inventory::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return [
            'name' => __('inventory::attributes.Item Name'),
            'category' => __('inventory::attributes.Item Category'),
            'unit_group' => __('inventory::attributes.Item Unit Group'),
            'is_active' => __('inventory::attributes.Is Active'),
        ];
    }

    public function toggleIsActive(string $itemSlug): void
    {
        $itemService = resolve(ItemService::class);

        /** @var Item $item */
        $item = $itemService->findByKey($itemSlug);

        $itemService->update($item, [
            'is_active' => ! $item->is_active,
        ]);

        $this->resetPage();
        Flux::toast(__('inventory::messages.Item Updated Successfully'), variant: 'success');
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Item Image'),
            ],
            [
                'label' => __('inventory::attributes.Item Name'),
            ],
            [
                'label' => __('inventory::attributes.Item Category'),
            ],
            [
                'label' => __('inventory::attributes.Item Unit Group'),
            ],
            [
                'label' => __('inventory::attributes.Item Brands Count'),
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
            ],
            [
                'label' => __('strings.Actions'),
            ],
        ];
        $itemService = resolve(ItemService::class);
        $rows = $itemService->getItems([
            ...$this->filters,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ]);

        return view('inventory::livewire.items-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Items'),
            ]);
    }
}
