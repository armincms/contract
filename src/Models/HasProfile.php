<?php

namespace Armincms\Contract\Models;

trait HasProfile
{
    /**
     * Get the fullname.
     */
    public function fullname()
    {
        return $this->firstname().PHP_EOL.$this->lastname();
    }

    /**
     * Get the firstname.
     */
    public function firstname()
    {
        return optional($this->profile)->get('firstname');
    }

    /**
     * Get the lastname.
     */
    public function lastname()
    {
        return optional($this->profile)->get('lastname');
    }
}
