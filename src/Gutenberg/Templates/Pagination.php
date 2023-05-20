<?php

namespace Armincms\Contract\Gutenberg\Templates;

use Zareismail\Gutenberg\Template;
use Zareismail\Gutenberg\Variable;

class Pagination extends Template
{
    /**
     * Register the given variables.
     */
    public static function variables(): array
    {
        return [
            Variable::make('current_page', __('Current page number')),
            Variable::make('first_page_url', __('Url of the first page')),
            Variable::make('from', __('First page number')),
            Variable::make('last_page', __('Last page number')),
            Variable::make('last_page_url', __('Last page url')),
            Variable::make('next_page_url', __('Next page url')),
            Variable::make('prev_page_url', __('Previous page number')),
            Variable::make('to', __('Last page number')),
            Variable::make('total', __('Total count of items')),
        ];
    }
}
