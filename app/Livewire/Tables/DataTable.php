<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use Livewire\WithPagination;

class DataTable extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    public int $perPage = 15;

    /** @var array<string,mixed> */
    public array $selected = [];

    public bool $selectAll = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection =
                $this->sortDirection === 'asc'
                    ? 'desc'
                    : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }
}
