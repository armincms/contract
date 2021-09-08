<?php

namespace Armincms\Contract\Models;

trait Authorizable  
{ 
    /**
     * Bootstrap the model instance.
     * 
     * @return 
     */
    public static function bootAuthorizable()
    {
        static::saving(function($model) {
            $model->ensureAuthenticatable();
        });
    }

    /**
     * Ensute that auth relation ship is filled.
     * 
     * @return void
     */
    public function ensureAuthenticatable()
    {
        if (! is_null($this->auth_id) && ! is_null($this->auth_type)) {
            return false;
        }

        $this->auth()->associate(request()->user());
    }

    /**
     * Query the realted Authenticatable user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auth()
    {
        return $this->morphTo();
    }

    public function scopeAuthorize($query, $user = null)
    {
        $user = is_null($user) ? request()->user() : $user;

        return $query
                    ->where($query->qualifyColumn('auth_id'), optional($user)->getKey())
                    ->where($query->qualifyColumn('auth_type'), optional($user)->getMorphClass());
    }
}
