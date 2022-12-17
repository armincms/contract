<?php

namespace Armincms\Contract\Nova;

use Armincms\Contract\Models\Authenticatable;
use Laravel\Nova\Http\Requests\NovaRequest;

trait Authorizable
{
    /**
     * Initialize the given index query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @param  string  $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function initializeQuery(NovaRequest $request, $query, $search, $withTrashed)
    {
        return parent::initializeQuery($request, $query, $search, $withTrashed)
                    ->when(static::authenticatable(), function ($query) {
                        $query->authorize();
                    });
    }

    /**
     * Determine if resource is Authorizable.
     *
     * @return bool
     */
    public static function authenticatable()
    {
        return static::newModel() instanceof Authenticatable;
    }
}
