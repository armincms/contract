<?php

namespace Armincms\Contract\Contracts;

interface Authenticatable
{
    /**
     * Query the realted Authenticatable user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auth();
}
