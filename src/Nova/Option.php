<?php

namespace Armincms\Contract\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

abstract class Option extends NovaResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Contract\Models\Option::class;

    /**
     * The option storage driver name.
     *
     * @var string
     */
    public static $store = 'database';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'value';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'key', 'value',
    ];

    /**
     * Where should the global search link to?
     *
     * @var string
     */
    public static $globalSearchLink = 'edit';

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group()
    {
        return __('Configuration');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            Text::make(__('Option Key'), 'key'),
            Text::make(__('Option Value'), 'value'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fieldsForUpdate(NovaRequest $request)
    {
        return [
            $this->buildAvailableFields($request, [])->findFieldByAttribute($this->resource->key),
        ];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (collect(static::options())) {
            (new static())->buildAvailableFields($request, [])
                ->authorized($request)
                ->each->resolveForAction($request)
                ->filter(function ($field) {
                    return ! static::store()->has($field->attribute);
                })
                ->each(function ($field) {
                    static::store()->put($field->attribute, $field->value, static::storeTag());
                });
        }

        return $query->tagged(static::storeTag());
    }

    /**
     * Get the store tag name.
     *
     * @return string
     */
    public static function storeTag(): string
    {
        return static::uriKey();
    }

    /**
     * Get the option store name.
     *
     * @return
     */
    public static function store()
    {
        return app('armincms.option')->store('database');
    }

    /**
     * Retrieve option by the key.
     *
     * @var  string
     * @var  mixed 
     *
     * @return mixed
     */
    public static function option($key, $default = null)
    {
        return data_get(static::options(), $key, $default);
    }

    /**
     * Indicate option existance.
     *
     * @var  string
     *
     * @return mixed
     */
    public static function has($key)
    {
        return array_key_exists($key, static::options());
    }

    /**
     * Retrieve all stored options.
     *
     * @return array
     */
    public static function options()
    {
        return once(function () {
            return static::store()->tag(static::storeTag());
        });
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can replicate the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can delete the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToDelete(Request $request)
    {
        throw Illuminate\Auth\Access\AuthorizationException::class;
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can view the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToView(Request $request)
    {
        throw Illuminate\Auth\Access\AuthorizationException::class;
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return false;
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return \Laravel\Nova\URL|string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }
}
