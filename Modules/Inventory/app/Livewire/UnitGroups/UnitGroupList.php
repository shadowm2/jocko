<?php

namespace Modules\Inventory\Livewire\UnitGroups;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;
use Modules\Inventory\Services\UnitGroupService;

class UnitGroupList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
    ];

    /** @var array<string, mixed> */
    public array $filters = self::FILTERS;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    protected function defaultTableFilters(): array
    {
        return self::FILTERS;
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('inventory::strings.Search Unit Groups')],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['name' => __('inventory::strings.Unit Group Name'), 'units_count' => __('inventory::strings.Unit Group Items Count')];
    }

    public function render(UnitGroupService $unitGroupService): View
    {
        $columns = [
            [
                'label' => __('inventory::strings.Unit Group Name'),
                'key' => 'name',
            ],
            [
                'label' => __('inventory::strings.Unit Group Items Count'),
                'key' => 'units_count',
            ],
            [
                'label' => '',
                'component' => 'inventory::unit-group-action-cell',
            ],
        ];
        $rows = $unitGroupService->getUnitGroups([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('inventory::livewire.unit-groups-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Unit Groups'),
            ]);
    }
}
