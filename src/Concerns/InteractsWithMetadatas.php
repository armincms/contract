<?php

namespace Armincms\Contract\Concerns;
 
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
 
trait InteractsWithMetadatas  
{     
    /**
     * The metadata's model attributes.
     *
     * @var array
     */
    private $metadataAttributes = [];  

    /**
     * Bootstrap the traits.
     *
     * @return void
     */
    public static function bootInteractsWithMetadatas() 
    { 
        static::saved(function($model) {    
            $model->updateMetadatas($model->getMetadataChanges());
        });
    }

    /**
     * Get metadata attribute changes.
     * 
     * @return array
     */
    public function getMetadataChanges()
    {
        return $this->metadataAttributes;
    }

    /**
     * Determin if has a metadata for given key.
     * 
     * @param  string  $key
     * @return boolean     
     */
    public function hasMetadata($key)
    {
        return $this->getRelationValue('metadatas')->firstWhere('key', $key) ? true : false;
    } 

    /**
     * Update metadatas for given associated array.
     * 
     * @param  array  $metadatas 
     * @return $this            
     */
    public function updateMetadatas(array $metadatas)
    { 
        $metadatas = empty($metadatas) || Arr::isAssoc($metadatas) ? (array) $metadatas : [$metadatas]; 
        $metadataModel = $this->getMetadataModel();

        $metadataModel::unguarded(function() use ($metadatas) {
            collect($metadatas)->each(function($value, $key) {
                $oldValue = $this->getMetadataAttributes()->get($key, new \stdClass());

                $oldValue == $value ||
                $this->metadatas()->updateOrCreate(compact('key'), compact('value'));
            }); 
        });

        return $this;
    }

    /**
     * Query related metadata
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneOrMany
     */
    public function metadatas()
    {
        return $this->hasMany($this->getMetadataModel());
    }

    /**
     * Get the realted metadata model.
     * 
     * @return string
     */
    abstract public function getMetadataModel();

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {  
        if ($this->isMetadataAttribute($key)) {
            return $this->getMetadataAttribute($key);
        }  

        if(! is_null($value = parent::getAttribute($key)) || $key === $this->getKeyName()) {
            return $value;
        }  

        return $this->getMetadataAttribute($key, $value);  
    }

    /**
     * Get all attribute from the metadata model.
     *
     * @param  string  $key
     * @return array
     */
    public function getMetadataAttributes()
    { 
        $metadatas = $this->getRelationValue('metadatas');

        return collect($metadatas)->mapWithKeys(function($metadata) {
            return [ 
                $metadata->key => $this->transformModelValue($metadata->key, $metadata->value) 
            ];
        });
    }

    /**
     * Get an attribute from the metadata model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getMetadataAttribute($key, $default = null)
    {
        $keyName = $this->parseMetadataAttribute($key); 
        
        return $this->getMetadataAttributes()->get($keyName, $default);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        $keyName = $this->parseMetadataAttribute($key);

        return tap(parent::setAttribute($keyName, $value), function() use ($key, $keyName) {
            if (! $this->isMetadataAttribute($key)) {
                return;
            }  

            $this->setMetadataAttribute($keyName, $this->attributes[$keyName]);
            unset($this->attributes[$keyName]);
        }); 
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setMetadataAttribute($key, $value)
    {
        $keyName = $this->parseMetadataAttribute($key); 

        $this->metadataAttributes[$keyName] = $value;

        return $this; 
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setMetadataAttributes(array $metadatas)
    {
        collect($metadatas)->each([$this, 'setMetadataAttribute']);

        return $this;
    }

    /**
     * Determin if given key should be store in metadata.
     * 
     * @param  string  $key
     * @return boolean     
     */
    public function isMetadataAttribute(string $key)
    {
        return Str::startsWith($key, 'metadata::');
    }

    /**
     * Get key name of metadata attribute.
     * 
     * @param  string $key 
     * @return string      
     */
    public function parseMetadataAttribute(string $key)
    {
        return collect(explode('::', $key))->pop(); 
    }
}
