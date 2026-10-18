<?php

use Livewire\Component;
use Modules\Car\Livewire\Forms\CarForm;

it('refuses previous images that are not media', function () {
    $form = new CarForm(new class extends Component {}, 'form');

    expect(fn () => $form->setPreviousImages(collect(['not media'])))
        ->toThrow(InvalidArgumentException::class, 'previous images should be an instance of Media');

    $form->setPreviousImages(null);
    expect($form->prev_images)->toBe([]);
});
