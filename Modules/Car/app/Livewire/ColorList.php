<?php

namespace Modules\Car\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Car\Services\ColorService;

class ColorList extends Component
{
    public function render(ColorService $colorService): View
    {
        $columns = [
            [
                'label' => __('car::strings.Color Name'),
                'key' => 'name',
            ],
            [
                'label' => __('car::strings.Color Code'),
                'component' => 'car::color-circle',
            ],
            [
                'label' => '',
                'component' => 'car::color-action-cell',
            ],
        ];
        $colors = $colorService->getColors();

        return view('car::livewire.colors-list', [
            ...compact('columns'),
            'rows' => $colors,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('car::strings.Colors'),
            ]);
    }
}
