<?php

namespace Modules\Inventory\Livewire\Concerns;

/**
 * Shared state behavior for paginated Livewire lists using x-table.filters.
 * The consuming component owns its filter fields and their default values.
 */
trait InteractsWithTableFilters
{
    /** @return array<string, mixed> */
    abstract protected function defaultTableFilters(): array;

    public function updatedFilters(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->filters = $this->defaultTableFilters();
        $this->resetPage();
    }

    public function clearFilter(string $name): void
    {
        $defaults = $this->defaultTableFilters();

        if (! array_key_exists($name, $defaults)) {
            return;
        }

        $this->filters[$name] = $defaults[$name];
        $this->resetPage();
    }
}
