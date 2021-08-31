<?php

namespace Armincms\Contract\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Zareismail\NovaPolicy\Concerns\InteractsWithPolicy;

class Admin extends Authenticatable
{
    use InteractsWithPolicy, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile' => 'collection',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return app()->make(\Armincms\Factories\AdminFactory::class);
    }

    /**
     * Determine the current user is Developer.
     * 
     * @return boolean
     */
    public function isDeveloper()
    { 
        return in_array($this->email, [
            'zarehesmaiel@gmail.com',
            config('superadmin.email'),
        ]);
    }
}
