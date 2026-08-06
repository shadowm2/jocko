<?php

namespace Modules\User\Livewire\Settings;

use Illuminate\View\View;
use Livewire\Component;

class Appearance extends Component
{
    public function render(): View
    {
        return \view('dashboard::livewire.settings.appearance')
            ->layoutData([
                'title' => __('dashboard::strings.Appearance settings'),
            ])
            ->layout('dashboard::layouts.app');
    }
}
