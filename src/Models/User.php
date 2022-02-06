<?php

namespace Armincms\Contract\Models;

use Armincms\Contract\Concerns\InteractsWithMetadatas;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
use Zareismail\NovaPolicy\Concerns\InteractsWithPolicy;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use InteractsWithPolicy;
    use HasApiTokens;
    use HasFactory;
    use HasProfile;
    use InteractsWithMetadatas;
    use Notifiable;
    use MustVerifyEmail;

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
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return app()->make(\Armincms\Factories\UserFactory::class);
    } 

    /**
     * Get the realted metadata model.
     * 
     * @return string
     */
    public function getMetadataModel()
    {
        return UserMetadata::class;
    } 

    public function getProfileAttribute()
    {
        return $this->getMetadataAttributes();
    }
}
