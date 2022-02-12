<?php

namespace Armincms\Contract\Providers; 

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Database\Schema\Blueprint;  
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;   

class BlueprintServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{ 
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {  
        $this->authentication();
        $this->markables();
        $this->multilinguals();
        $this->resources();
        $this->configurables();
        $this->other();
    }

    /**
     * Register any authentication blueprints.
     * 
     * @return void
     */
    protected function authentication()
    {  
        Blueprint::macro('auth', function(string $name = 'auth') {
            return $this->morphs($name); 
        });
    } 

    /**
     * Register any markable blueprints.
     * 
     * @return void
     */
    protected function markables()
    {  
        Blueprint::macro('markable', function(string $default = 'draft') {
            return $this->string('marked_as')->default($default); 
        }); 
    }

    /**
     * Register any multilingual blueprints.
     * 
     * @return void
     */
    protected function multilinguals()
    {   
        Blueprint::macro('locale', function() {
            return $this->string('locale', 10)->default(app()->getLocale())->index(); 
        });

        Blueprint::macro('multilingualRefer', function() {
            return $this->string('sequence_key', 100)->nullable()->index(); 
        });

        Blueprint::macro('multilingualSummary', function(string $name = 'name') {
            $this->summary($name);
            $this->locale($name); 
        });

        Blueprint::macro('multilingualContent', function(string $name = 'name') {
            $this->content($name);
            $this->locale($name); 
        });
    }  

    /**
     * Register any resource blueprints.
     * 
     * @return void
     */
    protected function resources()
    { 
        Blueprint::macro('resourceName', function(string $name = 'name') {
            return $this->string($name, 120); 
        });

        Blueprint::macro('resourceSlug', function(string $name = 'slug') {
            return $this->resourceName($name, 120)->nullable(); 
        });

        Blueprint::macro('resourceUri', function(string $name = 'uri') {
            // Text/Blob cannot be index by Mysql
            return $this->string($name)->nullable()->index(); 
        });

        Blueprint::macro('resourceSummary', function(string $name = 'summary') {
            return $this->tinyText('summary')->nullable(); 
        });

        Blueprint::macro('resourceHits', function(string $name = 'hits') {
            return $this->integer('hits')->unsigned()->default(0); 
        });

        Blueprint::macro('dropResourceHits', function(string $name = 'hits') {
            return $this->dropColumn([$name]); 
        });

        Blueprint::macro('resourceContent', function(string $name = 'content') {
            return $this->longText('content')->nullable(); 
        });

        Blueprint::macro('resourceMeta', function(string $name = 'meta') {
            return $this->json('meta')->nullable()->comment('resource meta values'); 
        });  
        
        Blueprint::macro('summary', function(string $name = 'name') {
            $this->resourceName($name);
            $this->resourceSlug(); 
            $this->resourceSummary(); 
            $this->resourceUri(); 
            $this->auth();
        });
        
        Blueprint::macro('content', function(string $name = 'name') {
            $this->summary($name);
            $this->resourceContent();
            $this->resourceMeta();  
        }); 
    }

    /**
     * Register any resource blueprints.
     * 
     * @return void
     */
    protected function configurables()
    { 
        Blueprint::macro('configuration', function(string $name = 'config') {
            return $this->json($name)->nullable(); 
        });

        Blueprint::macro('dropConfiguration', function(string $name = 'config') {
            return $this->dropColumn($name); 
        });

        Blueprint::macro('details', function(string $name = 'detail') {
            return $this->configuration($name)->nullable(); 
        });

        Blueprint::macro('dropDetails', function(string $name = 'detail') {
            return $this->dropConfiguration($name); 
        });
    }

    /**
     * Register any other blueprints.
     * 
     * @return void
     */
    protected function other()
    { 
        Blueprint::macro('price', function(string $name = 'price') {
            return $this->double($name, 12, 4)->default(0.00); 
        });

        Blueprint::macro('dropPrice', function(string $name = 'price') {
            return $this->dropColumn($name); 
        }); 

        Blueprint::macro('longPrice', function(string $name = 'price') {
            return $this->double($name, 16, 4)->default(0.00); 
        });

        Blueprint::macro('dropLongPrice', function(string $name = 'price') {
            return $this->dropColumn($name); 
        }); 
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
            \Illuminate\Console\Events\ArtisanStarting::class
        ];
    } 
}
