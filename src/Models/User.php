<?php

namespace Armincms\Contract\Models;

use Armincms\Contract\Concerns\InteractsWithMedia;
use Armincms\Contract\Concerns\InteractsWithMetadatas;
use Armincms\Contract\Concerns\InteractsWithWidgets;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Zareismail\NovaPolicy\Concerns\InteractsWithPolicy;

class User extends Authenticatable implements MustVerifyEmailContract, HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasProfile;
    use InteractsWithMedia;
    use InteractsWithMetadatas;
    use InteractsWithPolicy;
    use InteractsWithWidgets;
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar',
    ];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($model) {
            $model->metadatas()->delete();
        });
    }

    /**
     * Query the related Permission`s.
     */
    public function roles(): BelongsToMany
    {
        return $this->morphToMany(PolicyRole::class, 'user', 'policy_user_role', 'user_id')->using(
            \Zareismail\NovaPolicy\PolicyUserRole::class
        );
    }

    /**
     * Get the user avatar image.
     *
     * @return array
     */
    public function getAvatarAttribute()
    {
        return $this->getFirstMediasWithConversions()->get('avatar');
    }

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

    /**
     * Get the available media collections.
     */
    public function getMediaCollections(): array
    {
        return [
            'avatar' => [
                'conversions' => ['common'],
                'multiple' => false,
                'disk' => 'image',
                'limit' => 20, // count of images
                'accepts' => ['image/jpeg', 'image/jpg', 'image/png'],
            ],
        ];
    }

    /**
     * Serialize the model to pass into the client view.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     */
    public function serializeForWidget($request, $detail = true): array
    {
        return array_merge(parent::toArray(), $this->profile->toArray());
    }
}
