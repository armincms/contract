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
        Providers\AuthServiceProvider::class,
        Providers\MediaServiceProvider::class,
        Providers\NovaServiceProvider::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {    
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        app('config')->set('app.locale', 'fa');
    }
}
