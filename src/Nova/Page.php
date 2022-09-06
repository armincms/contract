<?php

namespace Armincms\Contract\Nova;

use Armincms\Papyrus\Nova\Resource;

class Page extends Resource
{
    use Localization;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Contract\Models\Page::class;
}
