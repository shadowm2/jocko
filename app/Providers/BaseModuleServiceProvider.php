<?php

namespace App\Providers;

use Livewire\Livewire;
use Nwidart\Modules\Support\ModuleServiceProvider;

class BaseModuleServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name;

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower;

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers;

    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/Modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'resources/lang'), $this->nameLower);
        }
    }

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }

    public function boot(): void
    {
        parent::boot();

        $this->loadLivewireComponents();
    }

    protected function loadLivewireComponents(): void
    {
        $reflection = new \ReflectionClass($this);
        /** @var string $filePath */
        $filePath = $reflection->getFileName();
        $directory = dirname($filePath).'/../Livewire/';

        if (! is_dir($directory)) {
            return;
        }

        /** @var list<string> $files */
        $files = glob($directory.'/*.php');
        foreach ($files as $file) {
            $className = basename($file, '.php');
            $namespace = 'Modules\\'.$this->name.'\\app\\Livewire\\'.$className;

            if (class_exists($namespace)) {
                $alias = str($className)->kebab()->toString();
                Livewire::component($alias, $namespace);
            }
        }
    }
}
