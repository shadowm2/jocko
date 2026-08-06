<?php

namespace Modules\Car\Livewire\Forms;

use Livewire\Form;
use Modules\Car\Models\Color;
use Modules\Car\Services\ColorService;

class ColorForm extends Form
{
    public ?int $id = null;

    public string $name;

    public ?string $title;

    public string $hex = '#000000';

    /** @var array<mixed,string> */
    protected array $rules = [
        'name' => 'required',
        'hex' => 'required',
    ];

    public function save(): void
    {
        $data = $this->validate();
        $colorService = resolve(ColorService::class);
        if ($this->id) {
            $colorService->update($this->id, $data);
        } else {
            $colorService->create($data);
        }
    }

    public function setColor(Color $color): void
    {
        $this->id = $color->id;
        $this->name = $color->name;
        $this->title = $color->title;
        $this->hex = $color->hex;
    }
}
