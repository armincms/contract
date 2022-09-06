<?php

namespace Armincms\Contract\Contracts;

interface Hitsable
{
    /**
     * Trigger the resource hits.
     *
     * @return void
     */
    public function viewed();
}
