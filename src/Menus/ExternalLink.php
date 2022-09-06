<?php

namespace Armincms\Contract\Menus;

class ExternalLink extends MenuItemType
{
    /**
     * Get the resource name.
     *
     * @return string
     */
    public static function resourceName(): string
    {
        return \Armincms\Contract\Nova\ExternalLink::class;
    }

    /**
     * Get the resource key name.
     *
     * @return string
     */
    public static function getKeyName(): string
    {
        return 'address';
    }
}
