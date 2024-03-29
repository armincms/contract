<?php

namespace Armincms\Contract\Providers;

use Armincms\Contract\Models\Admin;
use Armincms\Contract\Models\ExternalLink;
use Armincms\Contract\Models\Page;
use Armincms\Contract\Models\User;
use Armincms\Contract\Policies\AdminPolicy;
use Armincms\Contract\Policies\ExternalLinkPolicy;
use Armincms\Contract\Policies\MenuPolicy;
use Armincms\Contract\Policies\PagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelServiceProvider;
use Outl1ne\MenuBuilder\Models\Menu;

class AuthServiceProvider extends LaravelServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Admin::class => AdminPolicy::class,
        ExternalLink::class => ExternalLinkPolicy::class,
        Menu::class => MenuPolicy::class,
        Page::class => PagePolicy::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureAdminGuard();
        $this->registerPolicies();
    }

    /**
     * Configure admin guard.
     *
     * @return void
     */
    public function configureAdminGuard()
    {
        app('config')->set('auth.guards.admin', config('auth.guards.web'));
        app('config')->set('auth.providers.admins', config('auth.providers.users'));
        app('config')->set('auth.passwords.admins', config('auth.passwords.users'));
        app('config')->set('auth.providers.admins.model', Admin::class);
        app('config')->set('auth.passwords.admins.provider', 'admins');
        app('config')->set('auth.guards.admin.provider', 'admins');
        app('config')->set('auth.providers.users.model', User::class);
    }
}
