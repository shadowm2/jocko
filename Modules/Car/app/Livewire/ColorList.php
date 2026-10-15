<?php

namespace Modules\Car\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Car\Services\ColorService;

class ColorList extends Component
{
    use WithPagination;
    use InteractsWithTableFilters;

    public array $filters = ['search' => ''];
    public string $sortBy = 'hex';
    public string $sortDirection = 'asc';
    protected function defaultTableFilters(): array { return ['search' => '']; }
    private function filterDefinitions(): array { return [['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('car::strings.Search Colors')]]; }
    private function sortOptions(): array { return ['name' => __('car::strings.Color Name'), 'hex' => __('car::strings.Color Code')]; }

    public function render(ColorService $colorService): View
    {
        $columns = [
            [
                'label' => __('car::strings.Color Name'),
                'key' => 'name',
            ],
            [
                'label' => __('car::strings.Color Code'),
                'component' => 'car::color-circle',
            ],
            [
                'label' => '',
                'component' => 'car::color-action-cell',
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
