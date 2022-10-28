<?php

namespace Armincms\Contract\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Str;

class Option extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Query where the `tag` column has given value.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array|string  $tag
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeTagged($query, $tag)
    {
        return $query->whereIn('tag', (array) $tag);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute('key', $key);
        parent::setAttribute('value', $value);

        return $this;
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (parent::getAttribute('key') === $key) {
            $key = 'value';
        }

        return parent::getAttribute($key);
    }

    /**
     * Serialize the given value.
     *
     * @param  mixed  $value
     * @return string
     */
    public function setValueAttribute($value)
    {
        $result = serialize($value);

        if ($this->connection instanceof PostgresConnection && Str::contains($result, "\0")) {
            $result = base64_encode($result);
        }

        $this->attributes['value'] = $result;
    }

    /**
     * Unserialize the given value.
     *
     * @param  string  $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        if ($this->connection instanceof PostgresConnection && ! Str::contains($value, [':', ';'])) {
            $value = base64_decode($value);
        }

        return unserialize($value);
    }
}
