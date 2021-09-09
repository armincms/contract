<?php

namespace Armincms\Contract\Providers; 

use Armincms\Contract\Cypress\Home;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Database\Schema\Blueprint;  
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Zareismail\Gutenberg\Gutenberg;   

class GutenbergServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{   
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    { 
        Gutenberg::components([
            Home::class,
        ]); 
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    } 

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Zareismail\Cypress\Events\ServingCypress::class,
            \Laravel\Nova\Events\ServingNova::class,
        ];
    } 
}
