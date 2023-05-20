<?php

namespace Armincms\Contract\Models;

use Zareismail\NovaPolicy\PolicyRole as Model;
use Zareismail\NovaPolicy\PolicyUserRole;

class PolicyRole extends Model
{
    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($model) {
            $model->isForceDeleting() && $model->syncPermissions();
        });
    }

    /**
     * Query the related Permission`s.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'user', 'policy_user_role')
            ->using(PolicyUserRole::class);
    }

    /**
     * Query the related Permission`s.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admins()
    {
        return $this->morphedByMany(Admin::class, 'user', 'policy_user_role')
            ->using(PolicyUserRole::class);
    }
}
