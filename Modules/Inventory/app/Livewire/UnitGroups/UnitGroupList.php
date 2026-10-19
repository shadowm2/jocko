<?php

namespace Modules\Inventory\Livewire\UnitGroups;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Services\UnitGroupService;

class UnitGroupList extends Component
{
    use InteractsWithTableFilters;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
    ];

    /** @var array<string, mixed> */
    public array $filters = self::FILTERS;

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
        $rows = $unitGroupService->getUnitGroups($this->filters);

        return view('inventory::livewire.unit-groups-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Unit Groups'),
            ]);
    }
}
