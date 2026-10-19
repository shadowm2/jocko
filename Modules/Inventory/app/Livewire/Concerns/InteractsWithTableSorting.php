<?php

namespace Modules\Inventory\Livewire\Concerns;

/**
 * Sort state for paginated Livewire lists that offer sort options.
 * The consuming component owns $sortBy and $sortDirection.
 */
trait InteractsWithTableSorting
{
    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function toggleSortDirection(): void
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }
}
