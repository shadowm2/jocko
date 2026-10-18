<?php

use Modules\User\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('the sidebar opens every group above the current page', function (string $route) {
    $this->actingAs(User::factory()->create());

    $html = $this->get(route($route))->getContent();

    // The outer group plus the page's own group (Flux renders that one twice).
    expect(preg_match_all('/<ui-disclosure[^>]*\sopen[\s=>]/', $html))->toBe(3);
})->with([
    'warehouses.index',
    'stock-movements.index',
    'stock-movements.create',
    'stock-movements.transfer',
    'payments.index',
    'payments.create',
]);
