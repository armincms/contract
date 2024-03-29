<?php

namespace Armincms\Contract\Menus;

use Laravel\Nova\Fields\Select;
use Zareismail\Gutenberg\Gutenberg;

class Page extends MenuItemType
{
    /**
     * Get the resource name.
     */
    public static function resourceName(): string
    {
        return \Armincms\Contract\Nova\Page::class;
    }

    /**
     * Get the resource key name.
     *
     * @return string
     */
    public static function getKeyName()
    {
        return function ($resource) {
            return $resource->getKey();
        };
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array An array of fields.
     */
    public static function getFields(): array
    {
        return array_merge([
            Select::make(__('Target Fragment'), 'fragment')->options(function () {
                return Gutenberg::cachedFragments()->keyBy->getKey()->map->name;
            })
                ->required()
                ->rules('required'),
        ], parent::getFields());
    }

    /**
     * Get the rules for the resource.
     *
     * @return array A key-value map of attributes and rules.
     */
    public static function getRules(): array
    {
        return [
            'data->fragment' => 'required',
        ];
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
        return $query->hasLocale((array) $locale);
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
        if (is_null($fragment = Gutenberg::cachedFragments()->find($data['fragment']))) {
            return $value;
        }

        if (is_null($page = static::findPage($value))) {
            return $value;
        }

        return $fragment->getUrl($page->getUri());
    }

    /**
     * Find the page with the given key.
     *
     * @param  string  $key
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findPage($key)
    {
        $pages = \Cache::remember(static::class, 60, function () {
            $resourceClass = static::resourceName();

            return $resourceClass::newModel()->get();
        });

        return $pages->find($key);
    }
}
