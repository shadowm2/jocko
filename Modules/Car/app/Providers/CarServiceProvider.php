<?php

namespace Modules\Car\Providers;

use App\Providers\BaseModuleServiceProvider;

class CarServiceProvider extends BaseModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Car';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'car';

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
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
