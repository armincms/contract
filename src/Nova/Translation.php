<?php

namespace Armincms\Contract\Nova;

use Armincms\NovaTranslation\Nova\Translation as Resource;

class Translation extends Resource
{
    use Localization;

    /**
     * Get the avaialabe locales.
     */
    public static function getLocales(): array
    {
        return collect(app('application.locales'))->pluck('name', 'locale')->all();
    }
}
