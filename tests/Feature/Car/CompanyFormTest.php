<?php

use Livewire\Livewire;
use Modules\Car\Livewire\CompanyCreate;
use Modules\Car\Livewire\CompanyEdit;
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

it('renames a company, keeps its own name valid, and refuses another company\'s name', function () {
    $toyota = CarCompany::forceCreate(['name' => 'Toyota', 'slug' => 'toyota']);
    CarCompany::forceCreate(['name' => 'Kia', 'slug' => 'kia']);
    $edit = fn (string $name) => Livewire::test(CompanyEdit::class, ['company' => $toyota])
        ->assertSet('form.name', 'Toyota')
        ->set('form.name', $name)
        ->call('save');

    $edit('Toyota')->assertHasNoErrors();
    $edit('Kia')->assertHasErrors('form.name');
    $edit('')->assertHasErrors('form.name');
    expect($toyota->fresh()->name)->toBe('Toyota');

    $edit('Lexus')->assertHasNoErrors()->assertRedirect(route('companies.index'));
    expect($toyota->fresh()->name)->toBe('Lexus')
        ->and(CarCompany::count())->toBe(2);
});
