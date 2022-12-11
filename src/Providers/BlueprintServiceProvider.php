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
        Blueprint::macro('auth', function (string $name = 'auth') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->morphs($name);
        });
        Blueprint::macro('dropAuth', function (string $name = 'auth') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropMorphs($name);
        });
        Blueprint::macro('user', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->foreignIdFor(config('auth.providers.users.model'));
        });
        Blueprint::macro('dropUser', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropForeignIdFor(config('auth.providers.users.model'));
        });
    }

    /**
     * Register any markable blueprints.
     *
     * @return void
     */
    protected function markables()
    {
        Blueprint::macro('markable', function (string $default = 'draft') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->string('marked_as')->default($default);
        });
        Blueprint::macro('dropMarkable', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn('marked_as');
        });
    }

    /**
     * Register any multilingual blueprints.
     *
     * @return void
     */
    protected function multilinguals()
    {
        Blueprint::macro('locale', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->string('locale', 10)->default(app()->getLocale())->index();
        });
        Blueprint::macro('dropLocale', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn('locale');
        });

        Blueprint::macro('multilingualRefer', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->string('sequence_key', 100)->nullable()->index();
        });
        Blueprint::macro('dropMultilingualRefer', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropIndex('sequence_key');

            return $this->dropColumn('sequence_key');
        });

        Blueprint::macro('multilingualSummary', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->summary($name);
            $this->locale();
        });
        Blueprint::macro('dropMultilingualSummary', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropSummary($name);
            $this->dropLocale();
        });

        Blueprint::macro('multilingualContent', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->content($name);
            $this->locale($name);
        });
        Blueprint::macro('dropMultilingualContent', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropContent($name);
            $this->dropLocale();
        });
    }

    /**
     * Register any resource blueprints.
     *
     * @return void
     */
    protected function resources()
    {
        Blueprint::macro('resourceName', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->string($name, 120);
        });
        Blueprint::macro('dropResourceName', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn($name);
        });

        Blueprint::macro('resourceSlug', function (string $name = 'slug') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->resourceName($name, 120)->nullable();
        });
        Blueprint::macro('dropResourceSlug', function (string $name = 'slug') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn($name);
        });

        Blueprint::macro('resourceUri', function (string $name = 'uri') {
            // Text/Blob cannot be index by Mysql
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->string($name)->nullable()->index();
        });
        Blueprint::macro('dropResourceUri', function (string $name = 'uri') {
            // Text/Blob cannot be index by Mysql
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropIndex($name);

            return $this->dropColumn($name);
        });

        Blueprint::macro('resourceSummary', function (string $name = 'summary') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->tinyText($name)->nullable();
        });
        Blueprint::macro('dropResourceSummary', function (string $name = 'summary') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn($name);
        });

        Blueprint::macro('resourceHits', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->integer('hits')->unsigned()->default(0);
        });
        Blueprint::macro('dropResourceHits', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn('hits');
        });

        Blueprint::macro('resourceContent', function (string $name = 'content') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->longText($name)->nullable();
        });
        Blueprint::macro('dropResourceContent', function (string $name = 'content') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn($name);
        });

        Blueprint::macro('resourceMeta', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->json('meta')->nullable()->comment('resource meta values');
        });
        Blueprint::macro('dropResourceMeta', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn('meta');
        });

        Blueprint::macro('summary', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->resourceName($name);
            $this->resourceSlug();
            $this->resourceSummary();
            $this->resourceUri();
            $this->auth();
        });
        Blueprint::macro('dropSummary', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropResourceName($name);
            $this->dropResourceSlug();
            $this->dropResourceSummary();
            $this->dropResourceUri();
            $this->dropAuth();
        });

        Blueprint::macro('content', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->summary($name);
            $this->resourceContent();
            $this->resourceMeta();
        });
        Blueprint::macro('dropContent', function (string $name = 'name') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropSummary($name);
            $this->dropResourceContent();
            $this->dropResourceMeta();
        });
    }

    /**
     * Register any resource blueprints.
     *
     * @return void
     */
    protected function configurables()
    {
        Blueprint::macro('configuration', function (string $name = 'config') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->json($name)->nullable();
        });
        Blueprint::macro('dropConfiguration', function (string $name = 'config') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn($name);
        });

        Blueprint::macro('details', function (string $name = 'detail') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->configuration($name)->nullable();
        });
        Blueprint::macro('dropDetails', function (string $name = 'detail') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
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
        Blueprint::macro('price', function (string $name = 'price') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->double($name, 12, 4)->default(0.00);
        });
        Blueprint::macro('dropPrice', function (string $name = 'price') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn($name);
        });

        Blueprint::macro('longPrice', function (string $name = 'price') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->double($name, 16, 4)->default(0.00);
        });
        Blueprint::macro('dropLongPrice', function (string $name = 'price') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->dropColumn($name);
        });

        Blueprint::macro('currency', function (string $name = 'currency') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->string($name, 10)->default('IRR');
        });
        Blueprint::macro('dropCurrency', function (string $name = 'currency') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
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
            \Illuminate\Console\Events\ArtisanStarting::class,
        ];
    }
}
