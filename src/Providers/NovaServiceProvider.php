<?php

namespace Armincms\Contract\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Resource;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app('config')->set('nova.path', 'cp');
        app('config')->set('nova.guard', 'admin');

        parent::boot();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            \Armincms\Contract\Nova\Tools\Menu::make(),
            \Armincms\Bios\Bios::make(),
        ];
    }

    /**
     * Register the application's Nova resources.
     *
     * @return void
     */
    protected function resources()
    {
        $namespace = 'Armincms\\Contract\\Nova\\';
        $directory = dirname(__DIR__).DIRECTORY_SEPARATOR.'Nova';

        $resources = [];

        foreach ((new Finder)->in($directory)->files() as $resource) {
            $resource = $namespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($resource->getPathname(), $directory.DIRECTORY_SEPARATOR)
            );

            if (is_subclass_of($resource, Resource::class) &&
                ! (new ReflectionClass($resource))->isAbstract()) {
                $resources[] = $resource;
            }
        }

        Nova::resources(collect($resources)->sort()->all());
    }
}
