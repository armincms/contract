<?php

namespace Armincms\Contract\Nova; 

use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use DmitryBubyakin\NovaMedialibraryField\TransientModel;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

trait Fields  
{   
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
        return tap(Image::make($name), function($field) use ($collection, $delimiter) { 

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
            ->actionText(__('Add new meta'));
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
