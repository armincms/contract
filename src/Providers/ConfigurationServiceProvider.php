<?php

namespace Armincms\Contract\Providers;
     
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;  
use Illuminate\Support\Str; 
use OptimistDigital\MenuBuilder\MenuItemTypes\BaseMenuItemType; 
use ReflectionClass; 
use Symfony\Component\Finder\Finder; 

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
        $this->menus();
        
        if (! $this->app->runningInConsole()) {
            $this->setUncacheableConfigurations(); 
        }
    }  

    /**
     * Set configurations that could not be cached.
     * 
     * @return void
     */
    public function setUncacheableConfigurations()
    {
        app('config')->set('nova-menu.locales', function() {
            return (array) app("nova-targomaan.locales");
        });   
    }

    /**
     * Register the application's menus.
     *
     * @return void
     */
    protected function menus()
    { 
        $this->mergeConfigFrom(dirname(dirname(dirname(__FILE__))).'/config/nova-menu.php', 'nova-menu');

        $namespace = 'Armincms\\Contract\\Menus\\';
        $directory = dirname(__DIR__).DIRECTORY_SEPARATOR.'Menus'; 

        $menus = (array) config('nova-menu.menu_item_types');

        foreach ((new Finder)->in($directory)->files() as $menu) {
            $menu = $namespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($menu->getPathname(), $directory.DIRECTORY_SEPARATOR)
            );  

            if (is_subclass_of($menu, BaseMenuItemType::class) &&
                ! (new ReflectionClass($menu))->isAbstract()) {
                $menus[] = $menu;
            }
        }

        app('config')->set('nova-menu.menu_item_types', $menus);
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
