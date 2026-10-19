<?php

use Modules\User\Models\User;

// Real browser: what Livewire/Alpine/Flux do on the page, which the Feature tests cannot see.
const BROWSER_LIST_ROUTES = [
    'brands.index', 'cars.index', 'categories.index', 'cities.index', 'colors.index',
    'companies.index', 'countries.index', 'items.index', 'orders.index', 'payments.index',
    'provinces.index', 'purchases.index', 'stock-movements.index', 'suppliers.index',
    'units.groups.index', 'units.index', 'users.index', 'warehouses.index',
    'warehouses.items.index', 'warehouses.items.low-stock',
];

beforeEach(fn () => $this->actingAs(User::factory()->create()));

test('every list page loads without script errors', function () {
    visit(array_map(fn (string $route) => route($route), BROWSER_LIST_ROUTES))->assertNoSmoke();
});

test('the filter drawer opens with its sort options', function () {
    visit(route('warehouses.index'))
        ->assertDontSee(__('strings.Sort By'))
        ->click(__('strings.Filters'))
        ->assertSee(__('strings.Sort By'));
});
