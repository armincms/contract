<?php

namespace Armincms\Contract\Models;

trait HasProfile
{
    /**
     * Get the fullname.
     *
     * @return
     */
    public function fullname()
    {
        return $this->firstname().PHP_EOL.$this->lastname();
    }

    /**
     * Get the firstname.
     *
     * @return
     */
    public function firstname()
    {
        return optional($this->profile)->get('firstname');
    }

    /**
     * Get the lastname.
     *
     * @return
     */
    public function lastname()
    {
        return optional($this->profile)->get('lastname');
    }
}
