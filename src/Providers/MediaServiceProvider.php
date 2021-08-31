<?php

namespace Armincms\Contract\Providers;
    
use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\ServiceProvider as LaravelServiceProvider; 
use Infinety\Filemanager\FilemanagerTool;
use Laravel\Nova\Nova; ;

class MediaServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
        Gate::define('viewFileManager', function() {
            return null;
        });
        
        Nova::serving(function() {
            $this->servingNova();
        });
    }  

    protected function servingNova()
    {
        Nova::tools([
            FilemanagerTool::make()->canSee(function($request) {
                return $request->user()->can('viewFileManager');
            }),
        ]);
    }
}
