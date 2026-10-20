<?php

namespace Modules\Inventory\Livewire\Units;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Services\UnitGroupService;
use Modules\Inventory\Services\UnitService;

class UnitList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    /** @var array<string, mixed> */
    public array $filters = [
        'search' => '',
        'unit_group_id' => '',
        'is_active' => [],
        'is_base' => [],
    ];

    public string $sortBy = 'unit_group_id';

    public string $sortDirection = 'asc';

    /** @var array<int, string> */
    #[Locked]
    public array $unitGroupOptions = [];

    protected function defaultTableFilters(): array
    {
        return [
            'search' => '',
            'unit_group_id' => '',
            'is_active' => [],
            'is_base' => [],
        ];
    }

    public function mount(UnitGroupService $unitGroupService): void
    {
        $this->unitGroupOptions = $unitGroupService
            ->getUnitGroups(paginate: false)
            ->mapWithKeys(fn ($group) => [$group->id => $group->name])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('inventory::strings.Search Units')],
            ['name' => 'unit_group_id', 'type' => 'select', 'label' => __('inventory::attributes.Unit Group'), 'placeholder' => __('strings.All'), 'options' => $this->unitGroupOptions],
            ['name' => 'is_active', 'type' => 'checkboxes', 'label' => __('inventory::attributes.Is Active'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
            ['name' => 'is_base', 'type' => 'checkboxes', 'label' => __('inventory::attributes.Is Base'), 'options' => ['1' => __('strings.Yes'), '0' => __('strings.No')]],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return [
            'unit_group_id' => __('inventory::attributes.Unit Group'),
            'name' => __('inventory::attributes.Unit Name'),
            'symbol' => __('inventory::attributes.Unit Symbol'),
            'conversion_factor' => __('inventory::attributes.Conversion Factor'),
        ];
    }

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
            ],
            [
                'label' => __('inventory::attributes.Unit Symbol'),
            ],
            [
                'label' => __('inventory::attributes.Unit Group Name'),
            ],
            [
                'label' => __('inventory::attributes.Conversion Factor'),
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
            ],
            [
                'label' => __('inventory::attributes.Is Base'),
            ],
            [
                'label' => '',
            ],
        ];
        $rows = $unitService->getUnits([
            ...$this->filters,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ]);

        return view('inventory::livewire.units-list', [
            ...compact('columns', 'rows'),
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Units'),
            ]);
    }
}
