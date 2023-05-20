<?php

namespace Armincms\Contract\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

abstract class Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the admin.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $admin
     * @return mixed
     */
    public function view(Authenticatable $user, Model $model)
    {
    }

    /**
     * Determine whether the user can create admins.
     *
     * @return mixed
     */
    public function create(Authenticatable $user)
    {
    }

    /**
     * Determine whether the user can update the admin.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $admin
     * @return mixed
     */
    public function update(Authenticatable $user, Model $model)
    {
    }

    /**
     * Determine whether the user can delete the admin.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $admin
     * @return mixed
     */
    public function delete(Authenticatable $user, Model $model)
    {
    }
}
