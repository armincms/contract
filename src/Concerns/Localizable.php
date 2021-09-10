<?php

namespace Armincms\Contract\Concerns;

use Armincms\Contract\Casts\Url;
 
trait Localizable  
{    
    /**
     * Query where has the app locale.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeLocalize($query)
    {
        return $query->hasLocale((array) app()->getLocale());
    }

    /**
     * Query where has the given locales.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeHasLocale($query, array $locales)
    {
        return $query->whereIn($this->getQualifiedLocaleName(), $locales);
    }
 
    /**
     * Get the table qualified locale name.
     *
     * @return string
     */
    public function getQualifiedLocaleName()
    {
        return $this->qualifyColumn($this->getLocaleName());
    }

    /**
     * Get the locale value of the model.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->{$this->getLocaleName()};
    }

    /**
     * Get the locale for the model.
     *
     * @return string
     */
    public function getLocaleName()
    {
        return 'locale';
    }
}
