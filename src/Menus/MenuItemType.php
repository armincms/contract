<?php

namespace Armincms\Contract\Menus;

use Armincms\Contract\Gutenberg\Templates\MenuItem;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MenuBuilder\MenuItemTypes\BaseMenuItemType;
use Zareismail\Gutenberg\Gutenberg;

abstract class MenuItemType extends BaseMenuItemType
{
    /**
     * Get the menu link identifier that can be used to tell different custom
     * links apart (ie 'page' or 'product').
     *
     **/
    public static function getIdentifier(): string
    {
        $resource = static::resourceName();

        return $resource::uriKey();
    }

    /**
     * Get menu link name shown in  a dropdown in CMS when selecting link type
     * ie ('Product Link').
     *
     **/
    public static function getName(): string
    {
        $resource = static::resourceName();

        return $resource::label();
    }

    /**
     * Get menu link type.
     *
     * Choose 'custom' if you only want to render custom fields.
     *
     * @return string text|select|static-url
     **/
    public static function getType(): string
    {
        return 'select';
    }

    /**
     * Get list of options shown in a select dropdown.
     *
     * Should be a map of [key => value, ...], where key is a unique identifier
     * and value is the displayed string.
     *
     **/
    public static function getOptions($locale): array
    {
        $resourceClass = static::resourceName();
        $query = $resourceClass::newModel()->newQuery();
        $key = static::getKeyName();

        return static::buildIndexQuery($query, $locale)
            ->get()
            ->mapInto($resourceClass)
            ->keyBy($key)
            ->map->title()
            ->toArray();
    }

    /**
     * Build an "index" query for the given locale.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $locale
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public static function buildIndexQuery($query, $locale)
    {
        $resourceClass = static::resourceName();
        $request = app(NovaRequest::class);

        return $resourceClass::buildIndexQuery($request, $query);
    }

    /**
     * Get the subtitle value shown in CMS menu items list.
     *
     * @param $data The data from item fields.
     * @return string
     **/
    public static function getDisplayValue($value, ?array $data, $locale)
    {
        $resourceClass = static::resourceName();

        if (is_null($resource = $resourceClass::newModel()->find($value))) {
            return $value;
        }

        return (new $resourceClass($resource))->title();
    }

    /**
     * Get the value of the link visible to the front-end.
     *
     * Can be anything. It is up to you how you will handle parsing it.
     *
     * This will only be called when using the nova_get_menu()
     * and nova_get_menus() helpers or when you call formatForAPI()
     * on the Menu model.
     *
     * @param $value The key from options list that was selected.
     * @param $data The data from item fields.
     * @return any
     */
    public static function getValue($value, ?array $data, $locale)
    {
        return $value;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array An array of fields.
     */
    public static function getFields(): array
    {
        return [
            Select::make(__('Display Template'), 'template')
                ->nullable()
                ->options(function () {
                    return Gutenberg::cachedTemplates()
                        ->where('template', MenuItem::class)
                        ->keyBy->getKey()
                        ->map->name;
                }),
        ];
    }

    /**
     * Get the rules for the resource.
     *
     * @return array A key-value map of attributes and rules.
     */
    public static function getRules(): array
    {
        return [];
    }

    /**
     * Get data of the link visible to the front-end.
     *
     * Can be anything. It is up to you how you will handle parsing it.
     *
     * This will only be called when using the nova_get_menu()
     * and nova_get_menus() helpers or when you call formatForAPI()
     * on the Menu model.
     *
     * @param  null  $data Field values
     * @return any
     */
    public static function getData($data = null)
    {
        return $data;
    }

    /**
     * Get the resource name.
     */
    abstract public static function resourceName(): string;

    /**
     * Get the resource key name.
     *
     * @return string
     */
    abstract public static function getKeyName();
}
