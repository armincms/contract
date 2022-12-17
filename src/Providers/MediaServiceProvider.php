<?php

namespace Armincms\Contract\Providers;

use Armincms\Contract\Media\PathGenerator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Nova\Nova;

class MediaServiceProvider extends LaravelServiceProvider
{
    /**
     * List of the storage disks.
     *
     * @var
     */
    protected $storageDisks = [
        'image' => 'images',
        'video' => 'videos',
        'audio' => 'audios',
        'document' => 'documents',
        'file' => 'other',
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->storages();
        $this->spatie();

        Gate::define('viewFileManager', function () {
            return null;
        });

        Nova::serving(function () {
            $this->servingNova();
        });
    }

    /**
     * regsiter any filesystem storages.
     *
     * @return void
     */
    public function storages()
    {
        collect($this->storageDisks)->each(function ($path, $name) {
            $this->app['config']->set("filesystems.disks.{$name}", [
                'driver' => 'local',
                'root' => storage_path("app/public/{$path}"),
                'url' => env('APP_URL').'/storage/'.$path,
                'visibility' => 'public',
            ]);
        });
    }

    /**
     * Configure spatie media library.
     *
     * @return void
     */
    public function spatie()
    {
        $this->app['config']->set('media-library.path_generator', PathGenerator::class);
    }

    /**
     * Regsiter any nova services.
     *
     * @return void
     */
    protected function servingNova()
    {
        Nova::tools([]);
    }
}
