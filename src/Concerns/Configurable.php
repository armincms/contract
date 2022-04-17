<?php

namespace Armincms\Contract\Concerns;
 
trait Configurable  
{  
    /**
     * Bootstrap the model instance.
     * 
     * @return 
     */
    public function initializeConfigurable()
    {
        $this->casts = array_merge(['configuration' => 'array'], (array) $this->casts); 
    } 

    /**
     * Retrive value from meta values for the given key.
     * 
     * @param  string $key     
     * @param  mixed $default 
     * @return mixed          
     */
    public function config(string $key = null, $default = null)
    {
        return is_null($key)
            ? $this->configuration
            : data_get($this->configuration, $key, $default);
    } 
}
