<?php

namespace Armincms\Contract\Nova; 

use Armincms\Fields\Targomaan;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\FieldCollection as Collection;

class FieldCollection extends Collection
{
    /**
     * Find a given field by its attribute.
     *
     * @param  string  $attribute
     * @param  mixed  $default
     * @return \Laravel\Nova\Fields\Field|null
     */
    public function findFieldByAttribute($attribute, $default = null)
    {
        if (! Str::contains($attribute, '::')) {
            return parent::findFieldByAttribute($attribute, $default);
        }

        return $this->whereInstanceOf(Targomaan::class)
                    ->map->findFieldByAttribute($attribute)
                    ->filter()
                    ->first();
    }
}