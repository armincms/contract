<?php

namespace Armincms\Contract\Gutenberg\Templates; 

use Zareismail\Gutenberg\Template; 
use Zareismail\Gutenberg\Variable;

class MenuItem extends Template 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('id', __('Menu item ID')),

            Variable::make('name', __('Menu item name')),

            Variable::make('url', __('Menu item url address')),

            Variable::make('target', __('Menu item target')),

            Variable::make('hasChildren', __('Indicates that menu has submenu')),

            Variable::make('childrens', __('HTML string of the menu item submenus')),

            Variable::make('depth', __('Depth of the menu [starting from zero]')),
        ];
    } 
}
