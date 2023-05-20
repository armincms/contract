<?php

namespace Armincms\Contract\Gutenberg\Templates;

use Zareismail\Gutenberg\Template;
use Zareismail\Gutenberg\Variable;

class Navbar extends Template
{
    /**
     * The logical group associated with the template.
     *
     * @var string
     */
    public static $group = 'Menu';

    /**
     * Register the given variables.
     */
    public static function variables(): array
    {
        return [
            Variable::make('name', __('Menu Name')),

            Variable::make('items', __('Rendered Menu Items')),
        ];
    }
}
