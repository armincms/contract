<?php

namespace Armincms\Contract\Providers;
     
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;  
use Illuminate\Support\Str;  

class ConfigurationServiceProvider extends LaravelServiceProvider
{ 
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {  
        app('config')->set('app.locale', 'fa');
        app('config')->set('sluggable.source', 'name');
        app('config')->set('sluggable.method', [Str::class, 'sluggable']);
    }  

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    { 
        Str::macro('sluggable', function ($string, $separator) {
            $slug = mb_strtolower(
                preg_replace('/([?]|\p{P}|\s)+/u', $separator, $string)
            );

            return trim($slug, $separator);
        });
    }
}
