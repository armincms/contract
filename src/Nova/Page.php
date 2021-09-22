<?php

namespace Armincms\Contract\Nova;

use Armincms\Papyrus\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest; 

class Page extends Resource
{      
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Contract\Models\Page::class;
}
