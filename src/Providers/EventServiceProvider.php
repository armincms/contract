<?php

namespace Armincms\Contract\Providers; 
  
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;   

class EventServiceProvider extends ServiceProvider
{ 
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Zareismail\Cypress\Events\BootstrapingLayout::class => [ 
            \Armincms\Contract\Listeners\BootstrapingLayout::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = []; 
}
