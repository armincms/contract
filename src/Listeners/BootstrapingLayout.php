<?php

namespace Armincms\Contract\Listeners;

use Armincms\Contract\Cypress\Plugins\SmartMeta;

class BootstrapingLayout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    { 
        $event->layout->prependPlugins([
            SmartMeta::make(),
        ]); 
    }
}
