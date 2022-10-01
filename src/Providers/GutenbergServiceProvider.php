<?php

namespace Armincms\Contract\Providers;

use Armincms\Contract\Cypress\Fragments\Page;
use Armincms\Contract\Cypress\Home;
use Armincms\Contract\Cypress\Widgets\Menu;
use Armincms\Contract\Cypress\Widgets\SinglePage;
use Armincms\Contract\Gutenberg\Templates\MenuItem;
use Armincms\Contract\Gutenberg\Templates\Navbar;
use Armincms\Contract\Gutenberg\Templates\Pagination;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Zareismail\Gutenberg\Gutenberg;

class GutenbergServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Gutenberg::components([
            Home::class,
        ]);

        Gutenberg::fragments([
            Page::class,
        ]);

        Gutenberg::widgets([
            Menu::class,
            SinglePage::class,
        ]);

        Gutenberg::templates([
            MenuItem::class,
            Navbar::class,
            Pagination::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Zareismail\Cypress\Events\ServingCypress::class,
            \Laravel\Nova\Events\ServingNova::class,
        ];
    }
}
