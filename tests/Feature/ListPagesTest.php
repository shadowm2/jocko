<?php

use Modules\User\Models\User;

const LIST_ROUTES = [
    'brands.index', 'cars.index', 'categories.index', 'cities.index', 'colors.index',
    'companies.index', 'countries.index', 'items.index', 'orders.index', 'payments.index',
    'provinces.index', 'purchases.index', 'stock-movements.index', 'suppliers.index',
    'units.groups.index', 'units.index', 'users.index', 'warehouses.index',
    'warehouses.items.index', 'warehouses.items.low-stock',
];

test('every list page renders with the filter drawer', function (string $route) {
    $this->actingAs(User::factory()->create());

    $this->get(route($route))
        ->assertOk()
        ->assertSee('x-data="{ filtersOpen: false }"', false);
})->with(LIST_ROUTES);

// Item brands and user cars need a parent record, so they are rendered in ListSearchFiltersTest.
test('every list view is wrapped in the filter drawer', function () {
    $views = glob(base_path('Modules/*/resources/views/livewire/{,*/}*-list.blade.php'), GLOB_BRACE);

    expect($views)->not->toBeEmpty();

    $without = collect($views)
        ->reject(fn (string $view) => str_contains(file_get_contents($view), '<x-table.filter-drawer'))
        ->map(fn (string $view) => str_replace(base_path().'/', '', $view))
        ->values()
        ->all();

    expect($without)->toBe([]);
});
