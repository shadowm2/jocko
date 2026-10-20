<?php

namespace Modules\Car\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Car\Services\ColorService;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;

class ColorList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    /** @var array<string, mixed> */
    public array $filters = ['search' => ''];

    public string $sortBy = 'hex';

    public string $sortDirection = 'asc';

    protected function defaultTableFilters(): array
    {
        return ['search' => ''];
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('car::strings.Search Colors')]];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['name' => __('car::strings.Color Name'), 'hex' => __('car::strings.Color Code')];
    }

    public function render(ColorService $colorService): View
    {
        $columns = [
            [
                'label' => __('car::strings.Color Name'),
            ],
            [
                'label' => __('car::strings.Color Code'),
            ],
            [
                'label' => '',
            ],
        ];
        $colors = $colorService->getColors([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('car::livewire.colors-list', [
            ...compact('columns'),
            'rows' => $colors,
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('car::strings.Colors'),
            ]);
    }
}
