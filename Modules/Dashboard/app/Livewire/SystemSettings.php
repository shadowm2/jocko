<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Dashboard\Services\SettingService;

class SystemSettings extends Component
{
    /**
     * group => field => value. Secret fields stay empty: stored secrets are
     * never loaded into component state, so they never reach the browser.
     *
     * @var array<string, array<string, ?string>>
     */
    public array $values = [];

    public function mount(SettingService $settings): void
    {
        foreach ($settings->fields() as $key => $field) {
            $this->values[$field['group']][$field['name']] = ($field['secret'] ?? false) ? '' : ($settings->all()[$key] ?? '');
        }
    }

    public function save(SettingService $settings): void
    {
        $rules = [];
        $attributes = [];
        foreach ($settings->fields() as $field) {
            $path = "values.{$field['group']}.{$field['name']}";
            $rules[$path] = $field['rules'] ?? 'nullable|string|max:255';
            $attributes[$path] = __($field['label']);
        }
        $this->validate($rules, attributes: $attributes);

        foreach ($settings->fields() as $key => $field) {
            $value = trim((string) $this->values[$field['group']][$field['name']]);

            // A blank secret means "keep what is stored"; use clear() to remove it.
            if (($field['secret'] ?? false) && $value === '') {
                continue;
            }

            $settings->set($key, $value);
            $this->values[$field['group']][$field['name']] = ($field['secret'] ?? false) ? '' : $value;
        }

        Flux::toast(__('dashboard::messages.Settings Saved Successfully'), variant: 'success');
    }

    public function clear(string $key, SettingService $settings): void
    {
        // The key comes from the browser, so only act on declared fields.
        abort_unless(isset($settings->fields()[$key]), 404);

        $settings->forget($key);
        Flux::toast(__('dashboard::messages.Setting Cleared Successfully'), variant: 'success');
    }

    public function render(SettingService $settings): View
    {
        return view('dashboard::livewire.system-settings', [
            'groups' => config('dashboard.settings'),
            'stored' => array_keys($settings->all()),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData(['title' => __('dashboard::strings.System Settings')]);
    }
}
