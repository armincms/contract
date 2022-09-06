<?php

namespace Armincms\Contract\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

trait SoftDeletes
{
    /**
     * Determine whether the user can restore the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $admin
     * @return mixed
     */
    public function restore(Authenticatable $user, Model $model)
    {
    }

    /**
     * Determine whether the user can permanently delete the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $admin
     * @return mixed
     */
    public function forceDelete(Authenticatable $user, Model $model)
    {
    }
}
