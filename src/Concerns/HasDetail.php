<?php

namespace Armincms\Contract\Concerns;

trait HasDetail
{
    /**
     * Bootstrap the model instance.
     *
     * @return
     */
    public function initializeHasDetail()
    {
        $this->casts = array_merge(['detail' => 'array'], (array) $this->casts);
    }

    /**
     * Retrive value from meta values for the given key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function detail(string $key = null, $default = null)
    {
        return is_null($key)
            ? $this->detail
            : data_get($this->detail, $key, $default);
    }
}
