<?php

namespace Armincms\Contract\Nova;

use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Manogi\Tiptap\Tiptap;
use MZiraki\PersianDateField\PersianDate;
use MZiraki\PersianDateField\PersianDateTime;

trait Fields
{
    /**
     * Create new currency field.
     *
     * @return \Laravel\Nova\Fields\Field
     */
    public function currencyField(string $name, string $attribute = 'price')
    {
        return tap(Currency::make($name, $attribute), function ($field) {
        });
    }

    /**
     * Create new date field.
     *
     * @return \Laravel\Nova\Fields\Field
     */
    public function dateField(string $name, string $attribute = 'date')
    {
        return app()->getLocale() == 'fa'
            ? PersianDate::make($name, $attribute)
            : Date::make($name, $attribute);
    }

    /**
     * Create new datetime field.
     *
     * @return \Laravel\Nova\Fields\Field
     */
    public function datetimeField(string $name, string $attribute = 'date')
    {
        return app()->getLocale() == 'fa'
            ? PersianDateTime::make($name, $attribute)
            : DateTime::make($name, $attribute);
    }

    /**
     * Create new Medialibrary field.
     *
     * @param  string  $name
     * @param  string  $collection
     * @return \Laravel\Nova\Fields\Field
     */
    public function medialibrary($name, $collection = 'image')
    {
        return tap(Medialibrary::make(__($name), $collection), function ($field) {
            $field->autouploading();
        });
    }

    /**
     * Create new Medialibrary field.
     *
     * @param  string  $collection
     * @return \Laravel\Nova\Fields\Field
     */
    public function resourceEditor(string $name, string $attribute = 'content')
    {
        switch (General::option('editor', Tiptap::class)) {
            case Tiptap::class:
                return Tiptap::make(__($name), $attribute)->headingLevels([1, 2, 3, 4, 5, 6])->buttons([
                    'heading', 'italic', 'bold', 'link', 'code', 'strike', 'underline', 'highlight',
                    'bulletList', 'orderedList', 'textAlign', 'rtl',
                    'br',
                    'image', 'codeBlock', 'blockquote', 'horizontalRule', 'hardBreak', 'table', 'history', 'editHtml',
                ]);

            default:
                return Trix::make(__($name), $attribute)->withFiles('file');
        }
    }

    /**
     * Create new Image field.
     *
     * @param  string  $delimiter
     * @return \Laravel\Nova\Fields\Field
     */
    public function resourceImage(string $name, string $collection = 'image', $delimiter = '::')
    {
        return tap(Image::make($name)->maxWidth(150), function ($field) use ($collection, $delimiter) {
            $callback = function ($request, $model, $attribute) use ($delimiter, $collection) {
                return function () use ($model, $attribute, $delimiter, $collection) {
                    $locale = Str::after($attribute, $delimiter);
                    $model = $model->translations->where('locale', $locale)->first(null, $model);

                    $model
                        ->addMediaFromRequest($attribute)
                        ->toMediaCollection($collection);
                };
            };

            $field
                ->store($callback)
                ->delete(function ($request, $model) use ($collection, $delimiter) {
                    $locale = Str::after($request->route('field'), $delimiter);
                    $model
                        ->translations->where('locale', $locale)
                        ->first(null, $model)
                        ->clearMediaCollectionExcept($collection);

                    return true;
                })
                ->resolveUsing(function ($value, $model, $attribute) use ($collection, $delimiter) {
                    $locale = Str::after($attribute, $delimiter);

                    return $model->translations
                        ->where('locale', $locale)
                        ->first(null, $model)
                        ->getFirstMediaUrl($collection);
                })
                ->preview(function ($value) {
                    return $value;
                });
        });
    }

    /**
     * Create KeyValue field to handle metadata.
     *
     * @return \Laravel\Nova\Fields\Field
     */
    public function resourceMeta(string $name, string $attribute = 'meta')
    {
        return KeyValue::make($name, $attribute)
            ->keyLabel(__('Meta Key'))
            ->valueLabel(__('Meta Value'))
            ->actionText(__('Add new meta'))
            ->disableAddingRows()
            ->disableDeletingRows()
            ->disableEditingKeys()
            ->default(function () {
                return [
                    'title' => '',
                    'description' => '',
                    'tags' => 'separate, your tags, by comma',
                ];
            });
    }

    /**
     * Create stack field to display resource urls for given fragment.
     *
     * @param  string  $fragment
     * @return \Laravel\Nova\Fields\Field
     */
    public function resourceUrls(string $name = 'Urls')
    {
        $urls = $this->resource->getKey() ? $this->resource->urls() : [];

        return Stack::make(__($name), collect($urls)->map(function ($url) {
            return Line::make($url['website'], function () use ($url) {
                return "<a class='no-underline dim text-primary' href='".
                        $url['url'].
                        "' target='".
                        static::uriKey().
                        "'><sapn class=font-semibold>".
                        $url['website'].
                        '</sapn> <small>['.
                        $url['name'].
                        ']</small></a>';
            })->asHtml();
        })->all());
    }

    /**
     * Get the fields that are available for the given request.
     *
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    public function availableFields(NovaRequest $request)
    {
        return FieldCollection::make(parent::availableFields($request));
    }
}
