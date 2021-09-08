<?php

namespace Armincms\Contract\Models;
 
trait InteractsWithMedia  
{ 
    use \Spatie\MediaLibrary\InteractsWithMedia; 

    /**
     * Register spatie media collections.
     * 
     * @return void
     */
    public function registerMediaCollections(): void
    {
        collect($this->getMediaCollections())->each(function($config, $name) { 
            $drivers = $config['conversions'] ?? ['common'];
            $multiple= $config['multiple'] ?? false;
            $limit   = $multiple ? ($config['limit'] ?? 20) : 1;

            $this
                ->addMediaCollection($name)
                ->useDisk($config['disk'] ?? 'public')
                ->acceptsMimeTypes($config['accepts'] ?? []) 
                ->useFallbackUrl($config['fallback'] ?? '')
                ->onlyKeepLatest($limit)
                ->registerMediaConversions(function() use ($drivers) {
                    $this->registerConversions($drivers);
                });
        });
    }

    /**
     * Register media conversions from given drivers.
     *  
     * @param  array $drivers 
     * @return void          
     */
    protected function registerConversions($drivers)
    { 
        collect($drivers)->each(function($driver) {
            if (! app('conversion')->has($driver)) {
                return;
            }

            $schemas = app('conversion')->driver($driver)->schemas();

            collect($schemas)->each(function($schema, $name) use ($driver) {
                $this->registerMediaConversion("{$driver}-{$name}", (array) $schema);
            });
        });    
    }

    /**
     * Register new media conversion with the givenv schema.
     * 
     * @param  string $name   
     * @param  array  $schema 
     * @return void         
     */
    public function registerMediaConversion(string $name, array $schema)
    {      
        tap($this->addMediaConversion($name), function($conversion) use ($schema) {
            $conversion->width($schema['width'] ?? 0);
            $conversion->height($schema['height'] ?? 0);
            $conversion->quality(100 - ($schema['compress'] ?? 0));
            $conversion->extractVideoFrameAtSecond(1);

            if(isset($schema['extension'])) { 
                $conversion = $conversion->format($schema['extension']); 
            } else {
                $conversion = $conversion->keepOriginalImageFormat();
            } 

            if(isset($schema['background'])) {
                $conversion = $conversion->background($schema['background']);
            }  

            $this
                ->parseManipulations($schema['manipulations'] ?? ['crop' => 'crop-center'])
                ->each(function($position, $manipulation) use ($conversion, $schema) {  
                    $conversion->{$manipulation}($position, $schema['width'] ?? 0, $schema['height'] ?? 0);
                });  
        });
    } 

    /**
     * Sanitize the given manipulation.
     * 
     * @param  array|string $manipulations 
     * @return array                
     */
    public function parseManipulations($manipulations)
    {
        if(is_string($manipulations)) {
            $manipulations = [$manipulations];
        } 

        return collect($manipulations)->mapWithKeys(function($value, $key) {
            if(is_numeric($key)) {
                $key = $value;
                $value = 'center'; 
            }

            return [
                $key => $value
            ];
        });
    }

    /**
     * Get the lista of schemas.
     * 
     * @return array
     */
    public function schemas()
    {
        return collect($this->getMediaCollections())->flatten()->flatMap(function($driver) {
            if (! app('conversion')->has($driver)) {
                return [];
            }

            return app('conversion')->driver($driver)->schemas();
        });
    }

    /**
     * Get the available media collections.
     * 
     * @return array
     */
    public function getMediaCollections(): array
    {
        return [
            'image' => [
                'conversions' => ['common'],
                'multiple'  => false,
                'disk'      => 'image',
                'limit'     => 20, // count of images
                'accepts'   => ['image/jpeg', 'image/jpg', 'image/png'],
            ],
        ];
    }
}
