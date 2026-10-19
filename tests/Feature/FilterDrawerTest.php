<?php

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Modules\Inventory\Livewire\Warehouses\WarehouseList;
use Modules\User\Models\User;

$definitions = [
    ['name' => 'search', 'type' => 'search', 'label' => 'Search'],
    ['name' => 'is_active', 'type' => 'checkboxes', 'label' => 'Active', 'options' => ['1' => 'Yes', '0' => 'No']],
    ['name' => 'type', 'type' => 'select', 'label' => 'Type', 'options' => ['a' => 'Alpha']],
];

$drawer = fn (array $values) => Blade::render(
    '<x-table.filter-drawer :values="$values" :filters="$filters">body</x-table.filter-drawer>',
    ['values' => $values, 'filters' => $definitions],
);

it('counts the active filters in the button badge', function () use ($drawer) {
    $html = $drawer(['search' => 'abc', 'is_active' => ['1'], 'type' => '']);

    expect($html)->toMatch('/bg-accent px-1\.5[^>]*>\s*2\s*</');
});

it('shows no badge or chips when nothing is filtered', function () use ($drawer) {
    $html = $drawer(['search' => '', 'is_active' => [], 'type' => '']);

    expect($html)->not->toMatch('/bg-accent px-1\.5/')
        ->and($html)->not->toContain('clearFilter(');
});

it('shows a removable chip per active filter, using option labels', function () use ($drawer) {
    $html = $drawer(['search' => 'abc', 'is_active' => ['1', '0'], 'type' => 'a']);

    expect($html)->toContain('Search: abc')
        ->toContain('Active: Yes, No')
        ->toContain('Type: Alpha')
        ->toContain("wire:click=\"clearFilter('search')\"")
        ->toContain("wire:click=\"clearFilter('type')\"");
});

it('renders its content inside an accessible dialog panel', function () use ($drawer) {
    $html = $drawer([]);

    expect($html)->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('@keydown.escape.window="filtersOpen = false"')
        ->toContain('body');
});

// A disabled fieldset blurs the focused input, which broke typing in a filter.
it('never disables the filter inputs while a request is loading', function () use ($definitions) {
    $html = Blade::render('<x-table.filters :filters="$filters" clear-action="clearFilters" panel />', ['filters' => $definitions]);

    expect($html)->not->toContain('wire:loading.attr="disabled"')
        ->toContain('wire:loading.class="opacity-60"')
        ->not->toContain('lg:grid-cols-4');
});

it('clears one filter without touching the others', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(WarehouseList::class)
        ->set('filters.search', 'main')
        ->set('filters.is_active', ['1'])
        ->call('clearFilter', 'search')
        ->assertSet('filters.search', '')
        ->assertSet('filters.is_active', ['1'])
        ->call('clearFilter', 'not-a-filter')
        ->assertSet('filters.is_active', ['1'])
        ->call('clearFilters')
        ->assertSet('filters.is_active', []);
});
