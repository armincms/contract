<?php

namespace Armincms\Contract\Concerns;

use Armincms\Contract\Nova\General;

trait InteractsWithMedia
{
    use \Spatie\MediaLibrary\InteractsWithMedia;

    /**
     * Get all medias with conversions.
     *
     * @return array
     */
    public function getMediasWithConversions()
    {
        $this->registerAllMediaConversions();

        return collect($this->getMediaCollections())->map(function ($collection, $name) {
            return $this->getMedia($name)->map(function ($media) {
                $callback = function ($value, $conversion) use ($media) {
                    if (! $media->hasGeneratedConversion($conversion)) {
                        $conversion = null;
                    }

                    return $media->getUrl($conversion);
                };

                return collect($media->getGeneratedConversions())->map($callback);
            });
        });
    }

    /**
     * Get all medias with conversions.
     *
     * @return array
     */
    public function getFirstMediasWithConversions()
    {
        $this->registerAllMediaConversions();

        return collect($this->getMediaCollections())->map(function ($collection, $name) {
            if (is_null($media = $this->getFirstMedia($name))) {
                return [];
            }

            $callback = function ($value, $conversion) use ($media) {
                if (! $media->hasGeneratedConversion($conversion)) {
                    $conversion = null;
                }

                return $media->getUrl($conversion);
            };

            return collect($media->getGeneratedConversions())->map($callback);
        });
    }

    /**
     * Register spatie media collections.
     */
    public function registerMediaCollections(): void
    {
        collect($this->getMediaCollections())->each(function ($config, $name) {
            $drivers = $config['conversions'] ?? ['common'];
            $multiple = $config['multiple'] ?? false;
            $limit = $multiple ? ($config['limit'] ?? 20) : 1;

            $this
                ->addMediaCollection($name)
                ->useDisk($config['disk'] ?? 'public')
                ->acceptsMimeTypes($config['accepts'] ?? [])
                ->useFallbackUrl($config['fallback'] ?? '')
                ->onlyKeepLatest($limit)
                ->registerMediaConversions(function () use ($drivers) {
                    $this->registerConversions($drivers);
                });
        });
    }

    /**
     * Register media conversions from given drivers.
     *
     * @param  array  $drivers
     * @return void
     */
    protected function registerConversions($drivers)
    {
        collect($drivers)->each(function ($driver) {
            $this->createDriverIfNotExists($driver);

            $schemas = app('conversion')->driver($driver)->schemas();

            collect($schemas)->each(function ($schema, $name) use ($driver) {
                $this->registerMediaConversion("{$driver}-{$name}", (array) $schema);
            });
        });
    }

    /**
     * Create new driver if not exists.
     *
     * @return $this
     */
    protected function createDriverIfNotExists(string $driver)
    {
        if (app('conversion')->has($driver)) {
            return;
        }

        app('conversion')->extend($driver, function () {
            return new \Armincms\Conversion\CommonConversion;
        });

        return $this;
    }

    /**
     * Register new media conversion with the givenv schema.
     *
     * @return void
     */
    public function registerMediaConversion(string $name, array $schema)
    {
        tap($this->addMediaConversion($name), function ($conversion) use ($schema) {
            $conversion->width($schema['width'] ?? 0);
            $conversion->height($schema['height'] ?? 0);
            $conversion->quality(100 - ($schema['compress'] ?? 0));
            $conversion->extractVideoFrameAtSecond(1);

            if (General::option('disable_image_optimize', false)) {
                $conversion->nonOptimized();
            }

            if (isset($schema['extension'])) {
                $conversion = $conversion->format($schema['extension']);
            } else {
                $conversion = $conversion->keepOriginalImageFormat();
            }

            if (isset($schema['background'])) {
                $conversion = $conversion->background($schema['background']);
            }

            $this
                ->parseManipulations($schema['manipulations'] ?? ['crop' => 'crop-center'])
                ->each(function ($position, $manipulation) use ($conversion, $schema) {
                    $conversion->{$manipulation}($position, $schema['width'] ?? 0, $schema['height'] ?? 0);
                });
        });
    }

    /**
     * Sanitize the given manipulation.
     *
     * @param  array|string  $manipulations
     * @return array
     */
    public function parseManipulations($manipulations)
    {
        if (is_string($manipulations)) {
            $manipulations = [$manipulations];
        }

        return collect($manipulations)->mapWithKeys(function ($value, $key) {
            if (is_numeric($key)) {
                $key = $value;
                $value = 'center';
            }

            return [
                $key => $value,
            ];
        });
    }

    /**
     * Get the lista of schemas.
     *
     * @return \Illuminate\Support\Collection
     */
    public function schemas()
    {
        return collect($this->getMediaCollections())->flatMap(function ($collection) {
            $drivers = $collection['conversions'] ?? ['common'];

            return collect($drivers)->flatMap(function ($driver) {
                if (! app('conversion')->has($driver)) {
                    return [];
                }

                return app('conversion')->driver($driver)->schemas();
            });
        });
    }

    /**
     * Get the lista of conversions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function conversions()
    {
        return collect($this->getMediaCollections())->flatMap(function ($collection) {
            $drivers = $collection['conversions'] ?? ['common'];

            return collect($drivers)->flatMap(function ($driver) {
                $this->createDriverIfNotExists($driver);

                $schemas = app('conversion')->driver($driver)->schemas();

                return collect($schemas)->keys()->map(function ($schema) use ($driver) {
                    return "{$driver}-{$schema}";
                });
            });
        });
    }

    /**
     * Get the available media collections.
     */
    public function getMediaCollections(): array
    {
        return [
            'image' => [
                'conversions' => ['common'],
                'multiple' => false,
                'disk' => 'image',
                'limit' => 20, // count of images
                'accepts' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
            ],
        ];
    }
}
