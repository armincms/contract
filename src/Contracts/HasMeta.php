<?php

namespace Armincms\Contract\Contracts;

interface HasMeta
{
    /**
     * Retrive value from meta values for the give nkey.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function metaValue(string $key, $default = null);
}
