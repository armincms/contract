<?php

namespace Armincms\Contract;

use Illuminate\Support\AggregateServiceProvider;

class ServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        Providers\NovaServiceProvider::class,
    ];  
}
