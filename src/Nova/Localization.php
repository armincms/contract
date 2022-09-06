<?php

namespace Armincms\Contract\Nova;

use Illuminate\Support\Str;

trait Localization
{
    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __(parent::label());
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __(Str::singular(parent::label()));
    }
}
