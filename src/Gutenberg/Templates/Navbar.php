<?php

namespace Armincms\Contract\Gutenberg\Templates; 

use Zareismail\Gutenberg\Template; 
use Zareismail\Gutenberg\Variable;

class Navbar extends Template 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [  
            variable::make('name', __('Menu Name')),

            variable::make('items', __('Rendered Menu Items')), 
        ];
    } 
}
