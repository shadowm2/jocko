<?php

namespace Modules\Inventory\Livewire\UnitGroups;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Services\UnitGroupService;

class UnitGroupList extends Component
{
    use WithPagination;

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
        $rows = $unitGroupService->getUnitGroups();

        return view('inventory::livewire.unit-groups-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Unit Groups'),
            ]);
    }
}
