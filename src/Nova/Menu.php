<?php

namespace Armincms\Contract\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest; 
use Laravel\Nova\Panel; 
use OptimistDigital\MenuBuilder\Nova\Fields\MenuBuilderField; 

class Menu extends Resource
{  
    use Authorizable; 
    use Localization;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \OptimistDigital\MenuBuilder\Models\Menu::class; 

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return [
            ID::make(__('ID'), 'id')->sortable(), 

            Text::make(__('Menu Name'), 'name')
                ->sortable()
                    ->required()
                ->rules('required', 'max:255'), 

            Panel::make(__('Menu Items'), [
                MenuBuilderField::make(__('Menu Item'), 'menu_items')  
                    ->onlyOnForms()  
                    ->maxDepth(10)
                    ->readonly(),
            ])
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Actions\CreateMenu::make()->standalone()->canSee(function($request) {
                return $request->user()->can('create', static::$model);
            }),
        ];
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
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
     * Determine if the current user can view the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToView(Request $request)
    {
        return false;
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
}
