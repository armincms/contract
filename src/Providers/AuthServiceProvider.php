<?php

namespace Armincms\Contract\Providers;

use Armincms\Contract\Models\Admin;
use Armincms\Contract\Models\User;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;  

class AuthServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        $this->configureAdminGuard(); 
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
    }
}
