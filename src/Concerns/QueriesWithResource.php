<?php

namespace Armincms\Contract\Concerns;

trait QueriesWithResource
{
    /**
     * Handles booting model.
     *
     * @return void
     */
    public static function bootQueriesWithResource()
    {
        static::addGlobalScope(function ($query) {
            return $query->when(static::resources(), function ($query) {
                return $query->resources(static::resources());
            });
        });
    }

    /**
     * Query with the given resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResources($query, $code)
    {
        return $query->whereIn($this->getQualifiedResourceColumn(), (array) $code);
    }

    /**
     * Get valid resources to query.
     *
     * @return array
     */
    public static function resources()
    {
        return [];
    }

    /**
     * Get the name of the "resource" column.
     *
     * @return string
     */
    public function getResourceColumn()
    {
        return defined('static::RESOURCE') ? static::RESOURCE : 'resource';
    }

    /**
     * Get the fully qualified "resource" column.
     *
     * @return string
     */
    public function getQualifiedResourceColumn()
    {
        return $this->qualifyColumn($this->getResourceColumn());
    }
}
