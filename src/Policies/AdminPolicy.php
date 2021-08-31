<?php

namespace Armincms\Contract\Policies;
 
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;  

class AdminPolicy
{
    use HandlesAuthorization; 
    
    /**
     * Determine whether the user can view the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Armincms\Sofre\Authenticatable  $admin
     * @return mixed
     */
    public function view(Authenticatable $user, Authenticatable $admin)
    { 
    }

    /**
     * Determine whether the user can create admins.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return mixed
     */
    public function create(Authenticatable $user)
    { 
    }

    /**
     * Determine whether the user can update the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Armincms\Sofre\Authenticatable  $admin
     * @return mixed
     */
    public function update(Authenticatable $user, Authenticatable $admin)
    { 
    }

    /**
     * Determine whether the user can delete the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Armincms\Sofre\Authenticatable  $admin
     * @return mixed
     */
    public function delete(Authenticatable $user, Authenticatable $admin)
    { 
    }

    /**
     * Determine whether the user can restore the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Armincms\Sofre\Authenticatable  $admin
     * @return mixed
     */
    public function restore(Authenticatable $user, Authenticatable $admin)
    { 
    }

    /**
     * Determine whether the user can permanently delete the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Armincms\Sofre\Authenticatable  $admin
     * @return mixed
     */
    public function forceDelete(Authenticatable $user, Authenticatable $admin)
    { 
    }
}
