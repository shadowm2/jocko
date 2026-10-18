<?php

use Livewire\Livewire;
use Modules\Car\Livewire\CompanyCreate;
use Modules\Car\Models\CarCompany;

it('shows validation errors on the company form instead of dumping the exception', function () {
    Livewire::test(CompanyCreate::class)
        ->set('form.name', '')
        ->call('save')
        ->assertHasErrors('form.name');

    expect(CarCompany::count())->toBe(0);
});

it('creates a company and refuses a duplicate name', function () {
    Livewire::test(CompanyCreate::class)
        ->set('form.name', 'Toyota')
        ->call('save')
        ->assertHasNoErrors();

    expect(CarCompany::where('name', 'Toyota')->count())->toBe(1);

    Livewire::test(CompanyCreate::class)
        ->set('form.name', 'Toyota')
        ->call('save')
        ->assertHasErrors('form.name');

    expect(CarCompany::count())->toBe(1);
});
