<?php

namespace Modules\Inventory\Livewire\Units;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Services\UnitService;

class UnitList extends Component
{
    use WithPagination;

    public function toggleIsActive(string $unitSlug): void
    {
        $unitService = resolve(UnitService::class);

        /** @var Unit $unit */
        $unit = $unitService->findByKey($unitSlug);
        $unitService->update($unit, [
            'is_active' => ! $unit->is_active,
        ]);
        Flux::toast(__('inventory::messages.Unit Updated Successfully'), variant: 'success');
    }

    public function toggleIsBase(string $unitSlug): void
    {
        $unitService = resolve(UnitService::class);
        /** @var Unit $unit */
        $unit = $unitService->findByKey($unitSlug);
        $unitService->update($unit, [
            'is_base' => ! $unit->is_base,
        ]);
        Flux::toast(__('inventory::messages.Unit Updated Successfully'), variant: 'success');
    }

    public function render(UnitService $unitService): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Unit Name'),
                'key' => 'name',
            ],
            [
                'label' => __('inventory::attributes.Unit Symbol'),
                'key' => 'symbol',
            ],
            [
                'label' => __('inventory::attributes.Unit Group Name'),
                'component' => 'inventory::unit-unit-group-cell',
            ],
            [
                'label' => __('inventory::attributes.Conversion Factor'),
                'key' => 'conversion_factor',
                'type' => 'float',
                'decimals' => 3,
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => __('inventory::attributes.Is Base'),
                'key' => 'is_base',
                'type' => 'switch',
                'action' => 'toggleIsBase',
            ],
            [
                'label' => '',
                'component' => 'inventory::unit-action-cell',
            ],
        ];
        $rows = $unitService->getUnits();

        return view('inventory::livewire.units-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Units'),
            ]);
    }
}
