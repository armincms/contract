<?php

namespace Armincms\Contract\Nova; 

use Amidesfahani\NovaPersianDate\NovaPersianDate;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use DmitryBubyakin\NovaMedialibraryField\TransientModel;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime; 
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Stack; 
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest; 

trait Fields  
{   
    /**
     * Create new currency field.
     * 
     * @param  string $name       
     * @param  string $attribute 
     * @return \Laravle\Nova\Fields\Field             
     */
    public function currencyField(string $name, string $attribute = 'price')
    {
        return tap(Currency::make($name, $attribute), function($field) {

        });
    }

    /**
     * Create new date field.
     * 
     * @param  string $name       
     * @param  string $attribute 
     * @return \Laravle\Nova\Fields\Field             
     */
    public function dateField(string $name, string $attribute = 'date')
    {
        return app()->getLocale() == 'fa' 
            ? NovaPersianDate::make($name, $attribute)->type('date')
            : Date::make($name, $attribute);
    }

    /**
     * Create new datetime field.
     * 
     * @param  string $name       
     * @param  string $attribute 
     * @return \Laravle\Nova\Fields\Field             
     */
    public function datetimeField(string $name, string $attribute = 'date')
    {
        return app()->getLocale() == 'fa' 
            ? NovaPersianDate::make($name, $attribute)->type('datetime')
            : DateTime::make($name, $attribute);
    }

    /**
     * Create new Medialibrary field.
     * 
     * @param  string $name       
     * @param  string $collection 
     * @return \Laravle\Nova\Fields\Field             
     */
    public function medialibrary($name, $collection = 'image')
    {
        return tap(Medialibrary::make(__($name), $collection), function($field) {
            $field->autouploading(); 
        });
    }

    /**
     * Create new Medialibrary field.
     * 
     * @param  string $name       
     * @param  string $collection 
     * @return \Laravle\Nova\Fields\Field             
     */
    public function resourceEditor(string $name, string $attribute = 'content')
    {
        return Trix::make(__($name), $attribute)->withFiles('file');
    } 

    /**
     * Create new Image field.
     * 
     * @param  string $name       
     * @param  string $collection 
     * @param  string $delimiter 
     * @return \Laravle\Nova\Fields\Field             
     */
    public function resourceImage(string $name, string $collection = 'image', $delimiter = '::')
    { 
        return tap(Image::make($name)->maxWidth(150), function($field) use ($collection, $delimiter) { 

            $callback = function($request, $model, $attribute) use ($delimiter, $collection) {
                return function() use ($request, $model, $attribute, $delimiter, $collection) {
                    $locale = Str::after($attribute, $delimiter);
                    $model = $model->translations->where('locale', $locale)->first(null, $model);
                     
                    $model
                        ->addMediaFromRequest($attribute)
                        ->toMediaCollection($collection);
                };
            };

            $field
                ->store($callback)
                ->delete(function($request, $model) use ($collection, $delimiter) {
                    $locale = Str::after($request->route('field'), $delimiter);
                    $model
                        ->translations->where('locale', $locale)
                        ->first(null, $model)
                        ->clearMediaCollectionExcept($collection);

                    return true;
                })
                ->resolveUsing(function($value, $model, $attribute) use ($collection, $delimiter) {
                    $locale = Str::after($attribute, $delimiter);  

                    return $model->translations
                                 ->where('locale', $locale)
                                 ->first(null, $model)
                                 ->getFirstMediaUrl($collection);
                })
                ->preview(function($value) {
                    return $value;
                });
        });
    }

    /**
     * Create KeyValue field to handle metadata.
     * 
     * @param  string $name      
     * @param  string $attribute 
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
            ->default(function() {
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
     * @param  string $fragment       
     * @param  string $name       
     * @return \Laravel\Nova\Fields\Field            
     */
    public function resourceUrls(string $name = 'Urls')
    {  
        $urls = $this->resource->getKey() ? $this->resource->urls() : [];

        return Stack::make(__($name), collect($urls)->map(function($url) {
            return Line::make($url['website'], function() use ($url) {
                return "<a class='no-underline dim text-primary' href='".
                        $url['url'].
                        "' target='".
                        static::uriKey().
                        "'><sapn class=font-semibold>".
                        $url['website'].
                        "</sapn> <small>[".
                        $url['name'].
                        "]</small></a>";
            })->asHtml(); 
        })->all());
    }

    /**
     * Get the fields that are available for the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    public function availableFields(NovaRequest $request)
    {
        return FieldCollection::make(parent::availableFields($request));
    }
}
