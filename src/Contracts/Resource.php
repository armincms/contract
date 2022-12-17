<?php

namespace Armincms\Contract\Contracts;

interface Resource
{
    /**
     * The resource title.
     *
     * @return void
     */
    public function title();

    /**
     * The resource description.
     *
     * @return void
     */
    public function description();

    /**
     * The resource tags.
     *
     * @return void
     */
    public function tags();
}
